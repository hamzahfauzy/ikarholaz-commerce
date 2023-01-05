<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\Payment;
use App\Models\Product;
use App\Models\WaBlast;
use App\Models\Customer;
use App\Models\Ref\Tripay;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Libraries\NotifAction;
use App\Models\TransactionItem;
use App\Models\CustomFieldValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class VoucherController extends Controller
{
    //
    public function paymentChannel()
    {
        $tripay = new Tripay(getenv('TRIPAY_PRIVATE_KEY'), getenv('TRIPAY_API_KEY'));
        return $tripay->curlAPI($tripay->URL_channelMp, '', 'GET');
    }

    public function listProducts()
    {
        $end_date_field = \App\Models\CustomField::where('class_target','App\Models\VoucherProduct')
                            ->where('field_key','tanggal_berakhir')
                            ->first();

        $products = CustomFieldValue::where('custom_field_id',$end_date_field->id)->whereRaw("STR_TO_DATE(field_value, '%Y-%m-%dT%TZ') > now()")->join('products','products.id','=','custom_field_values.pk_id')->get();

        return $products;
    }
    
    public function index(Request $request)
    {
        $products = $this->listProducts();
        if(empty($products))
        {
            WaBlast::webisnisSend($request->sender, $request->phone, 'Tidak ada voucher yang tersedia');

            return response()->json([
                'status' => 'fail',
                'message' => 'no voucher',
            ],400);
        }

        $message = "Berikut adalah daftar voucher yang tersedia. Silahkan di pilih sesuai dengan nomor urut voucher.
";
        foreach($products as $no => $product)
        {
            $no = $no+1;
            $message .= $no . ". ". $product->name."
";
        }
        $message .= "
_(cukup balas dengan nomer pilihannya saja. contoh: 2)_";

        WaBlast::webisnisSend($request->sender, $request->phone, $message);

        return response()->json([
            'status' => 'success',
            'message' => 'voucher send',
            // 'data' => $products
        ]);
         
    }

    public function buy(Request $request)
    {
        $products = $this->listProducts();
        $phone    = str_replace('+','',$request->phone);
        Log::info('Option : '.$request->option);
        if(strpos($request->option,"#") !== false)
        {
            $option = explode('#',$request->option); // 0 = option index, 1 = pg index
            $paymentChannel = (array) $this->paymentChannel();
            $payments = $paymentChannel['data'];
            $payment  = false;
            $paymentChannel = array_map(function($p){ return $p['code']; }, $paymentChannel['data']);
            $order_items_string = "";
            $pgIndex = $option[1]-1;
            if($pgIndex == count($payments))
            {
                $payment = 'cash';
            }
            else
            {
                if(!isset($payments[$pgIndex]))
                {
                    WaBlast::webisnisSend($request->sender, $request->phone, 'Maaf! Pilihan pembayaran yang anda pilih tidak valid. Silahkan ulangi pendaftaran.');
                    return response()->json([
                        'status' => 'failed',
                        'errors' => $error
                    ], 400);
                }
    
                $payment = $payments[$pgIndex];
                $order_items_string = "Biaya Administrasi : ".number_format($payment['total_fee']['flat'])."\n";
            }
            DB::beginTransaction();
            try {

                $email = $request->phone.'@randomuser.com';
                $user = User::updateOrCreate([
                    'email' => $email,
                ],[
                    'name' => $request->phone,
                    'email' => $email,
                    'password' => strtotime('now'),
                ]);

                $custData = [
                    'user_id' => $user->id,
                    'first_name' => $user->name,
                    'last_name' => ' ',
                    'email' => $email,
                    'phone_number' => $phone,
                ];
                
                // create customer first
                $customer = Customer::create($custData);

                // then create transaction
                $transaction = Transaction::create([
                    'customer_id' => $customer->id,
                    'status'      => 'checkout'
                ]);
                
                $singleProduct = $products[$option[0]-1];
                
                $transaction_item = TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $singleProduct->id,
                    'amount'         => 1,
                    'total'          => $singleProduct->price,
                    'notes'          => ' '
                ]);
                
                if(
                    (
                        $singleProduct->stock_status == 0 || 
                        empty($singleProduct->stock_status)
                    ) 
                    && 
                    $singleProduct->stock >= 1
                )
                {
                    $singleProduct->update([
                        'stock' => $singleProduct->stock - 1
                    ]);
                }
                
                $all_total_price = $singleProduct->price;

                $cart_name = $singleProduct->name;
                $order_items[] = [
                    'sku'       => $singleProduct->slug,
                    'name'      => $cart_name,
                    'price'     => (int) $singleProduct->price, // *cart()->get($cart->id),
                    'quantity'  => (int) 1
                ];

                $order_items_string .= $cart_name." x 1 : ".number_format($singleProduct->price)."\n";
                
                if(is_array($payment))
                {
                    $privateKey = getenv('TRIPAY_PRIVATE_KEY');
                    $merchantCode = getenv('TRIPAY_MERCHANT_CODE');
                    $merchantRef = strtotime('now').'-'.$transaction->id; // getenv('TRIPAY_MERCHANT_REF'); Kode Unik Transaksi
                    
                    $signature = hash_hmac('sha256', $merchantCode.$merchantRef.$all_total_price, $privateKey);
    
                    $data = [
                        'method'            => $paymentChannel[$pgIndex],
                        'merchant_ref'      => $merchantRef,
                        'amount'            => $all_total_price,
                        'customer_name'     => $customer->full_name,
                        'customer_email'    => $customer->email,
                        'customer_phone'    => $customer->phone_number,
                        'callback_url'      => route('tripay-callback'),
                        'order_items'       => $order_items,
                        'signature'         => hash_hmac('sha256', $merchantCode.$merchantRef.$all_total_price, $privateKey)
                    ];
    
                    $tripay = new Tripay($privateKey, getenv('TRIPAY_API_KEY'));
                    $response = $tripay->curlAPI($tripay->URL_transMp,$data,'POST');
                    if($response['success'] == false)
                    {
                        WaBlast::webisnisSend($request->sender, $phone, "Tripay Error : ". $response['message']);
                        return response()->json($response,400);
                    }
                    $response_data = $response['data'];
                    $payments = [
                        'transaction_id' => $transaction->id,
                        'total' => $all_total_price,
                        'admin_fee' => $payment['total_fee']['flat'],
                        'checkout_url' => $response_data['checkout_url'],
                        'payment_type' => $paymentChannel[$pgIndex],
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
                        'payment_type' => 'cash',
                        'merchant_ref'      => 'cash',
                        'status' => "UNPAID",
                        'payment_reference' => "",
                        'payment_code' => "",
                        'expired_time' => "",
                    ];
                }
                $_payment = Payment::create($payments);

                DB::commit();

                $advertisement = Advertisement::where('event','ORDER VOUCHER')->first();
                $ads_content   = $advertisement ? $advertisement->contents : '';

                if(env('WA_BLAST_URL') !== null && env('WA_BLAST_URL') !== ''):

                    $total = $all_total_price;

                    if(is_array($payment)) $total += $payment['total_fee']['flat'];

                    $notifAction = new NotifAction;
                    $message = $notifAction->checkoutVoucherWASuccess($transaction, $total, $customer, $_payment, $order_items_string)."
".$ads_content;
                    WaBlast::webisnisSend($request->sender, $phone, $message);

                endif;

                // return redirect()->to($response_data['checkout_url']);
                if($request->payment_method != 'cash')
                {
                    $msg = "Silahkan klik link berikut untuk menyelesaikan pembayaran ".$response_data['checkout_url'];
                    WaBlast::webisnisSend($request->sender, $phone, $msg);

                }

                return response()->json([
                    'status' => 'succes',
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
        }

        if(!isset($products[$request->option-1]))
        {
            WaBlast::webisnisSend($request->sender, $phone, 'Maaf! Voucher yang anda pilih tidak valid. Silahkan ulangi pembelian.');
            return response()->json([
                'status' => 'failed',
                'errors' => 'Maaf! Voucher yang anda pilih tidak valid. Silahkan ulangi pembelian.'
            ], 400);
        }
        $paymentChannel = (array) $this->paymentChannel();
        $payments = $paymentChannel['data'];
        $product  = $products[$request->option-1];
        // $paymentChannel = array_map(function($p){ return $p['code']; }, $paymentChannel['data']);
        $message = "Anda akan membeli *".$product->name."*
*Silahkan Pilih Metode Pembayaran :*
";
        foreach($payments as $i => $p)
        {
$message .= ($i+1).'. '.$p['code']."
";
        }
$message .= ($i+2).'. CASH (transfer ke rek BCA/Mandiri - manual konfirm)';
        // $paymentChannel = implode(',',$paymentChannel);
        WaBlast::webisnisSend($request->sender, $phone, $message);
        return response()->json([
            'status' => 'succes',
        ]);
    }
}
