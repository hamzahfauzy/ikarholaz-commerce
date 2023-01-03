<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Cart;
use App\Models\User;
use App\Models\Event;
use App\Models\Price;
use App\Models\Payment;
use App\Models\Product;
use App\Models\WaBlast;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Shipping;
use App\Models\Ref\Tripay;
use App\Models\Transaction;
use App\Models\ProductImage;
use App\Models\Ref\District;
use App\Models\Ref\Province;
use Illuminate\Http\Request;
use App\Libraries\NotifAction;
use App\Models\TransactionItem;
use App\Models\CustomFieldValue;
use App\Models\Ref\ShippingRates;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::where('is_dynamic',NULL)->doesntHave('parent')->orderby('created_at','desc')->paginate();
        return view('shop.index',compact('products'));
    }

    public function productList($slug)
    {
        $category = $slug == 'Uncategorized' ? '' : Category::where('slug',$slug)->firstOrFail();
        $products = $category->products()->where('is_dynamic',NULL)->doesntHave('parent')->orderby('created_at','desc')->paginate();

        if($slug == 'Uncategorized')
            $products = Product::doesntHave('parent')->doesntHave('categories')->orderby('created_at','desc')->paginate();

        return view('shop.product-list',compact('products','slug','category'));
    }

    public function productDetail($slug)
    {
        $product = Product::where('is_dynamic',NULL)->where('slug',$slug)->firstOrFail();
        return view('shop.product-detail',compact('product'));
    }

    public function productAction(Request $request)
    {
        Cart::add($request->product_id,$request->qty);
        if($request->action == 'checkout')
            return redirect()->route('shop.checkout');
        return redirect()->back()->with('success',__('Add to cart success'));
    }

    public function addToCart($slug)
    {
        $product = Product::where('is_dynamic',NULL)->where('slug',$slug)->firstOrFail();
        Cart::add($product->id);
        return redirect()->route('shop.cart');
    }

    public function checkout()
    {
        if(Cart::count() == 0)
            return redirect()->route('shop.cart');
        
        $provinces = Province::get();
        return view('shop.checkout',compact('provinces'));
    }

    public function cart()
    {
        return view('shop.cart');
    }

    public function cartRemove($id)
    {
        Cart::pop($id);
        return redirect()->back()->with('success',__('Cart item removed'));
    }

    public function cartUpdate(Request $request)
    {
        Cart::update($request->id, $request->qty);
        return redirect()->back()->with('success',__('Cart item updated'));
    }

    public function placeOrder(Request $request)
    {
        // validation place here

        $order_items_string = "";
        $order_items = [];
        $shipping_rates = $request->courier ? ShippingRates::init($request->dest_id,cart()->get_weight(),$request->courier)->get() : 0;
        $dest = $request->courier ? District::province($request->province_id)->find($request->dest_id) : [];
        
        if($request->payment_method != 'cash'){
            $tripay = new Tripay(getenv('TRIPAY_PRIVATE_KEY'), getenv('TRIPAY_API_KEY'));
            $payments = $tripay->curlAPI($tripay->URL_channelMp,'','GET');
            $payments = $payments['data'];
            $key = array_search($request->payment_method, array_column($payments, 'code'));
            $payment = $payments[$key];
        }

        $all_total_price = 0; 

        $order_items[] = [
            'sku'       => 'ongkir',
            'name'      => 'Ongkir',
            'price'     => $shipping_rates ? $shipping_rates[$request->service]->cost[0]->value : 0,
            'quantity'  => 1
        ];

        $order_items_string .= "Ongkir : ".number_format($shipping_rates ? $shipping_rates[$request->service]->cost[0]->value : $shipping_rates)."\n";
        
        if($request->payment_method != 'cash') $order_items_string .= "Biaya Administrasi : ".number_format($payment['total_fee']['flat'])."\n";

        $data = [];
        $data['request'] = $request->all();
        if($shipping_rates) $data['dest'] = $dest;
        $data['shipping'] = $shipping_rates ? $shipping_rates[$request->service] : $shipping_rates;
        $data['payment'] = $payment ?? "cash";

        DB::beginTransaction();
        try {
            // create user first if not exists
            $user = User::updateOrCreate([
                'email' => $request->email,
            ],[
                'name' => $request->first_name.' '.$request->last_name,
                'email' => $request->email,
                'password' => strtotime('now'),
            ]);

            $custData = [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ];

            if($shipping_rates)
            {
                $custData = array_merge($custData,[
                    'province_id' => $request->province_id,
                    'province_name' => $dest->province,
                    'district_id' => $request->dest_id,
                    'district_name' => $dest->type.' '.$dest->city_name,
                    'address' => $request->address,
                    'postal_code' => $request->postal_code,
                ]);
            }
            // create customer first
            $customer = Customer::create($custData);

            // then create transaction
            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'status'      => 'checkout'
            ]);

            foreach(cart()->all_lists()->get() as $index => $cart)
            {
                $notes = $cart->categories->contains(config('reference.voucher_kategori')) ? substr(md5(strtotime('now')),0,8) : $request->notes[$index];
                $transaction_item = TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $cart->id,
                    'amount'         => cart()->get($cart->id),
                    'total'          => $cart->price*cart()->get($cart->id),
                    'notes'          => $notes
                ]);

                $singleProduct = Product::find($cart->id);
                if(
                    (
                        $singleProduct->stock_status == 0 || 
                        empty($singleProduct->stock_status)
                    ) 
                    && 
                    $singleProduct->stock >= cart()->get($cart->id)
                )
                {
                    $singleProduct->update([
                        'stock' => $singleProduct->stock - cart()->get($cart->id)
                    ]);
                }

                $all_total_price += $cart->price*cart()->get($cart->id);

                $cart_name = ($cart->parent?$cart->parent->parent->name.' - ':'').$cart->name;
                $order_items[] = [
                    'sku'       => $cart->parent?$cart->parent->parent->slug:$cart->slug,
                    'name'      => $cart_name,
                    'price'     => (int) $cart->price, // *cart()->get($cart->id),
                    'quantity'  => (int) cart()->get($cart->id)
                ];

                $order_items_string .= $cart_name." x ".cart()->get($cart->id)." : ".number_format($cart->price*cart()->get($cart->id))."\n";

                // cart item
                if(isset($request->cart_item) && isset($request->cart_item[$cart->id]))
                {
                    $custom_fields = $request->cart_item[$cart->id];
                    foreach($custom_fields as $cf_id => $cf_value)
                    {
                        foreach($cf_value as $value)
                        {
                            if(empty($value)) $value = "-";
                            CustomFieldValue::create([
                                'custom_field_id' => $cf_id,
                                'pk_id' => $transaction_item->id,
                                'field_value' => $value
                            ]);
                        }
                    }
                }

                if($cart->custom_fields)
                {
                    $card_number = '';
                    foreach($cart->custom_fields as $cf)
                    {
                        if($cf->customField->field_key == 'nomor_kartu')
                            $card_number = $cf->field_value;
                    }
                    $card = Card::where('card_number',$card_number)->first();
                    $card->update([
                        'status' => 'Checkout'
                    ]);
                }
            }

            if($request->donasi > 0)
            {
                // create donasi product
                $product = Product::create([
                    'name' => 'Donasi - #'.$transaction->id,
                    'slug' => 'donasi-'.$transaction->id,
                    'base_price' => $request->donasi,
                    'description' => 'Donasi - #'.$transaction->id,
                    'stock' => 0,
                    'stock_status' => 'Dynamic Product',
                    'is_dynamic' => 1
                ]);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'amount'         => 1,
                    'total'          => $request->donasi
                ]);

                $order_items[] = [
                    'sku'       => 'donasi-'.$transaction->id,
                    'name'      => 'Donasi #'.$transaction->id,
                    'price'     => (int) $request->donasi,
                    'quantity'  => 1
                ];

                $order_items_string .= "Donasi : ".$request->donasi."\n";

                $all_total_price += (int) $request->donasi;
            }

            if($shipping_rates)
            {
                Shipping::create([
                    'transaction_id' => $transaction->id,
                    'fullname' => $user->name,
                    'province_id' => $request->province_id,
                    'province_name' => $dest->province,
                    'district_id' => $request->dest_id,
                    'district_name' => $dest->type.' '.$dest->city_name,
                    'address' => $request->address,
                    'postal_code' => $request->postal_code,
                    'courir_name' => $request->courier,
                    'courir_id' => 0,
                    'service_name' => $shipping_rates ? $shipping_rates[$request->service]->service : '',
                    'service_id' => $request->service ?? 0,
                    'service_rates' => $shipping_rates ? $shipping_rates[$request->service]->cost[0]->value : 0,
                ]);
            }


            $all_total_price += $shipping_rates ? $shipping_rates[$request->service]->cost[0]->value : 0;

            if($request->payment_method != 'cash'){
                $privateKey = getenv('TRIPAY_PRIVATE_KEY');
                $merchantCode = getenv('TRIPAY_MERCHANT_CODE');
                $merchantRef = strtotime('now').'-'.$transaction->id; // getenv('TRIPAY_MERCHANT_REF'); Kode Unik Transaksi
                
                $signature = hash_hmac('sha256', $merchantCode.$merchantRef.$all_total_price, $privateKey);

                $data = [
                    'method'            => $request->payment_method,
                    'merchant_ref'      => $merchantRef,
                    'amount'            => $all_total_price,
                    'customer_name'     => $user->name,
                    'customer_email'    => $user->email,
                    'customer_phone'    => $customer->phone_number,
                    'callback_url'      => route('tripay-callback'),
                    'order_items'       => $order_items,
                    'signature'         => hash_hmac('sha256', $merchantCode.$merchantRef.$all_total_price, $privateKey)
                ];
    
                $tripay = new Tripay($privateKey, getenv('TRIPAY_API_KEY'));
                $response = $tripay->curlAPI($tripay->URL_transMp,$data,'POST');
                if($response['success'] == false)
                {
                    return [$response,$data];
                    return redirect()->back()->withInput();
                }
                $response_data = $response['data'];
                $payments = [
                    'transaction_id' => $transaction->id,
                    'total' => $all_total_price,
                    'admin_fee' => $payment['total_fee']['flat'],
                    'checkout_url' => $response_data['checkout_url'],
                    'payment_type' => $request->payment_method,
                    'merchant_ref'      => $merchantRef,
                    'status' => $response_data['status'],
                    'payment_reference' => $response_data['reference'],
                    'payment_code' => $response_data['pay_code'],
                    'expired_time' => $response_data['expired_time'],
                ];
            }else{
                $payments = [
                    'transaction_id' => $transaction->id,
                    'total' => $all_total_price,
                    'admin_fee' => 0,
                    'checkout_url' => "",
                    'payment_type' => $request->payment_method,
                    'merchant_ref'      => $request->payment_method,
                    'status' => "UNPAID",
                    'payment_reference' => "",
                    'payment_code' => "",
                    'expired_time' => "",
                ];
            }

            $_payment = Payment::create($payments);
            cart()->clear();

            DB::commit();

            if(env('WA_BLAST_URL') !== null && env('WA_BLAST_URL') !== ''):

                $total = $all_total_price;

                if($request->payment_method != 'cash') $total += $payment['total_fee']['flat'];

                $notifAction = new NotifAction;
                $notifAction->checkoutSuccess($cart, $transaction, $total, $customer, $_payment, $order_items_string);

            endif;

            if($request->payment_method != 'cash'){
                return redirect()->to($response_data['checkout_url']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
        return redirect()->route('shop.thankyou');
    }

    function checkoutKta(Request $request)
    {
        $data = [];
        $nomor_kartu = $request->no_kartu_fix;
        $nomor_kartu = explode('.',$nomor_kartu);
        $tahun_lulus = ($nomor_kartu[0] < date('y') ? 20 : 19).$nomor_kartu[0];
        $harga = isset($request->digit) ? Price::get($request->digit) : 0;
        
        DB::beginTransaction();
        try {
            $data_desain = [];
            if($request->file('kustom_desain'))
            {
                $file_url = $request->file('kustom_desain')->store('products');
                $data_desain['price'] = env('HARGA_KUSTOM_DESAIN',0);
                $data_desain['file_url'] = $file_url;
            }
            else
            {
                $desain = Product::findOrFail($request->desain_id);
                $data_desain['price'] = $desain->price;
                $data_desain['file_url'] = $desain->thumb->file_url;
            }

            if($request->product_id){
                $product = Product::find($request->product_id);
            }else{
                $product = Product::create([
                    'name' => 'KTA - #'.$request->no_kartu_fix.' & Desain ('.$data_desain['price'].')',
                    'slug' => 'kta-'.$request->no_kartu_fix,
                    'base_price' => $harga+$data_desain['price'],
                    'description' => 'KTA - #'.$request->no_kartu_fix.', Nama Lengkap : '.$request->nama_lengkap.', Nama Kartu : '.$request->nama_tercetak_di_kartu.', Desain ('.$data_desain['price'].')',
                    'stock' => 0,
                    'stock_status' => 'Dynamic Product',
                    'is_dynamic' => 1
                ]);

                ProductImage::create([
                    'product_id' => $product->id,
                    'file_url' => $data_desain['file_url']
                ]); 
            }
            
            $product->set_custom_fields([
                'nama_lengkap' => $request->nama_lengkap,
                'nama_tercetak_di_kartu' => $request->nama_tercetak_di_kartu,
                'nomor_kartu' => $request->no_kartu_fix,
                'tahun_lulus' => $tahun_lulus,
                'pemesanan' => 'baru',
            ]);
            Card::create([
                'card_number' => $request->no_kartu_fix,
                'tahun' => $tahun_lulus,
                'unique_number' => $nomor_kartu[1],
                'name' => $request->nama_tercetak_di_kartu,
                'status' => 'Booking'
            ]);
            cart()->add($product->id);
            DB::commit();
            return redirect()->route('shop.checkout');
            //code...
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
