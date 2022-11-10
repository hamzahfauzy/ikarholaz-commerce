<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\Cart;
use App\Models\Event;
use App\Models\Price;
use App\Models\Alumni;
use App\Models\Jolali;
use App\Models\WaBlast;
use App\Models\{User,Customer,Transaction,TransactionItem,Product,Payment};
use App\Models\Ref\Tripay;
use App\Models\Ref\District;
use App\Models\Ref\Province;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Ref\ShippingRates;
use App\Http\Controllers\Controller;
use Validator;
use DB;
use App\Libraries\NotifAction;

class BaseController extends Controller
{
    //
    function getProvinces()
    {
        return Province::get();
    }

    function getAgenda(){
        return Event::take(3)->get();
    }

    function getJolali(){
        return Jolali::take(3)->get();
    }
    
    function getFields($fields)
    {    
        switch($fields){
            case "sektors":
                return file_get_contents('sektors.json');
            case "communities":
                return file_get_contents('communities.json');
            case "professions":
                return file_get_contents('professions.json');
            case "badan_hukums":
                return file_get_contents('badan_hukums.json');
            case "ijin_usahas":
                return file_get_contents('ijin_usahas.json');
            default:
                return null;
        }
    }

    function getDistrict($province_id)
    {
        return District::province($province_id)->get();
    }

    function getService($courier)
    {
        $weight = $_GET['weight'];
        $dest = $_GET['dest'];
        return ShippingRates::init($dest, $weight, $courier)->get();
    }

    public function paymentChannel()
    {
        $tripay = new Tripay(getenv('TRIPAY_PRIVATE_KEY'), getenv('TRIPAY_API_KEY'));
        return $tripay->curlAPI($tripay->URL_channelMp, '', 'GET');
    }

    public function getKartu($nomor)
    {
        return Card::where('unique_number', $nomor)->firstOrFail();
    }

    public function getNomorRegular($tahun_lulus)
    {
        $tahun_lulus = substr($tahun_lulus, 2, 2);
        $nomor_kartu = substr(strtotime('now'), 2, 8);
        return $tahun_lulus . '.' . $nomor_kartu;
    }

    public function getPrice($digit)
    {
        return number_format(Price::get($digit));
    }

    public function testWa()
    {
        $message = "Halo Bro

            Berikut ini adalah data order kamu
            Order ID: #1
            Rincian transaksi
            Oke
            
            Total: 10000
            
            Silahkan melakukan pembayaran melalui
            
            Terima kasih.";
        return WaBlast::send("082369378823", $message);
    }

    public function downloadPdf(Request $request)
    {
        $filename = md5(md5($request->NRA."".$request->created_at));
        $file_to_save = 'pdf/'.$filename.'.pdf';
        if(!file_exists($file_to_save))
            $this->generatePdf($request);
        return response()->json([
            'status' => file_exists($file_to_save),
            'file_url' => url()->to($file_to_save)
        ], 200);
    }

    public function sendPdf(Request $request)
    {
        $path = public_path('/assets/images/pemilu-bg.jpeg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        
        $barcode = file_get_contents("http://www.barcode-generator.org/phpqrcode/getCode.php?cht=qr&chl=https%3A%2F%2Fgerai.ikarholaz.id%2Fpdf%2F".$request->NRA.".pdf&chs=180x180&choe=UTF-8&chld=L|0");
        $base64_barcode = 'data:image/png;base64,' . base64_encode($barcode);

        $content = "<html><body><div style='padding-top:140px;position:realtive;width:500px;height:650px;margin:auto;'><img src=\"$base64\" style='position:absolute;top:40px;z-index:-1;width:500px;height:650px;object-fit:contain;' />";
        $content .= "<table border='1' cellpadding='5' cellspacing='0' width='500px' align='center'>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'><h1 style='color:red'>ARSIP PRIBADI<br>SANGAT RAHASIA</h1></td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'><h2>No. Bukti : #".$request->no_urut."</h2></td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>NAMA : ".$request->name."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>ALUMNI : ".$request->graduation_year."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>NRA : ".$request->NRA."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>MEMILIH : ".$request->candidate_name."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>WAKTU MEMILIH : ".$request->created_at."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'><img src='".$base64_barcode."' style='width:100px;height:100px;'></td>";
        $content .= "</tr>";
        $content .= "</table></div></body></html>";

        $pdf = PDF::loadHTML($content);
        $filename = md5(md5($request->NRA."".$request->created_at));
        $file_to_save = 'pdf/'.$filename.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->output());
        $pdf = PDF::loadHTML($content);
        $filename = md5(md5($request->NRA."".$request->created_at));
        $file_to_save = 'pdf/'.$filename.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->output()); 
        $alumni = Alumni::where('NRA',$request->NRA)->first();
        $message = "$request->name, $request->NRA telah menggunakan hak suara dengan memilih *$request->candidate_name* sebagai ketua umum IKARHOLAZ periode 2021-2024. Berikut adalah bukti surat suara Anda ".asset($file_to_save)."

_Mohon tidak menghapus notifikasi WA ini sampai program Munas berakhir sebagai bukti valid partisipasi dan suara anda._";

        // return WaBlast::send($alumni->user->email, $message);
        return WaBlast::sent($alumni->user->email, $message);
    }

    public function testPdf()
    {
        $path = public_path('/assets/images/pemilu-bg.jpeg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        $content = "<html><body><div style='padding-top:140px;position:realtive;width:400px;height:500px;margin:auto;'><img src=\"$base64\" style='position:absolute;top:40px;z-index:-1;width:400px;height:500px;object-fit:contain;' />";
        $content .= "<table border='1' cellpadding='5' cellspacing='0' width='400px' align='center'>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'><h2>KPU IKARHOLAZ</h2></td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>NAMA : Nama Pemilih</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>ALUMNI : 2010</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>NRA : 12.34678</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>TELAH MEMILIH : Kandidat</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>TANGGAL DAN WAKTU MEMILIH : Hari ini</td>";
        $content .= "</tr>";
        $content .= "</table></div></body></html>";
        return $content;
        // $pdf = PDF::loadHTML($content);
        // $file_to_save = 'pdf/'.$request->NRA.'.pdf';
        // //save the pdf file on the server
        // file_put_contents($file_to_save, $pdf->output()); 
    }

    function generatePdf(Request $request)
    {
        $path = public_path('/assets/images/pemilu-bg.jpeg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        
        $barcode = file_get_contents("http://www.barcode-generator.org/phpqrcode/getCode.php?cht=qr&chl=https%3A%2F%2Fgerai.ikarholaz.id%2Fpdf%2F".$request->NRA.".pdf&chs=180x180&choe=UTF-8&chld=L|0");
        $base64_barcode = 'data:image/png;base64,' . base64_encode($barcode);

        $content = "<html><body><div style='padding-top:140px;position:realtive;width:500px;height:650px;margin:auto;'><img src=\"$base64\" style='position:absolute;top:40px;z-index:-1;width:500px;height:650px;object-fit:contain;' />";
        $content .= "<table border='1' cellpadding='5' cellspacing='0' width='500px' align='center'>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'><h1 style='color:red'>ARSIP PRIBADI<br>SANGAT RAHASIA</h1></td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'><h2>No. Bukti : #".$request->no_urut."</h2></td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>NAMA : ".$request->name."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>ALUMNI : ".$request->graduation_year."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>NRA : ".$request->NRA."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>MEMILIH : ".$request->candidate_name."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>WAKTU MEMILIH : ".$request->created_at."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'><img src='".$base64_barcode."' style='width:100px;height:100px;'></td>";
        $content .= "</tr>";
        $content .= "</table></div></body></html>";

        $pdf = PDF::loadHTML($content);
        $filename = md5(md5($request->NRA."".$request->created_at));
        $file_to_save = 'pdf/'.$filename.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->output());

        return $file_to_save;
    }
    
    function orderTiket(Request $request)
    {
        $paymentChannel = (array) $this->paymentChannel();
        $payments = $paymentChannel['data'];
        $paymentChannel = array_map(function($p){ return $p['code']; }, $paymentChannel['data']);
        $paymentChannel = implode(',',$paymentChannel);
        // validation
        $validator = Validator::make($request->all(), [
          'slug' => 'required|exists:products',
          'name' => 'required',
          'pg'   => 'required|in:'.$paymentChannel,
        ], 
        [
            'slug.required' => 'Kode tiket tidak boleh kosong!',
            'slug.exists' => 'Kode tiket tidak valid!',
            'pg.required' => 'Metode pembayaran tidak boleh kosong!',
            'pg.in' => 'Metode pembayaran tidak valid!',
        ]);
        
        if ($validator->fails()) {
            $error =  $validator->getMessageBag()->first();
            WaBlast::webisnisSend($request->sender, $request->phone, $error);
            return response()->json([
                'status' => 'failed',
                'errors' => $error
            ], 400);
        }
        
        $key = array_search($request->payment_method, array_column($payments, 'code'));
        $payment = $payments[$key];
        $order_items_string = "Biaya Administrasi : ".number_format($payment['total_fee']['flat'])."\n";
        DB::beginTransaction();
        try {
            // create user first if not exists
            $user = User::create([
                'name' => $request->name,
                'email' => strtotime('now').'@randomuser.com',
                'password' => strtotime('now'),
            ]);

            $custData = [
                'user_id' => $user->id,
                'first_name' => $request->name,
                'last_name' => ' ',
                'email' => $user->email,
                'phone_number' => $request->phone,
            ];
            
            // create customer first
            $customer = Customer::create($custData);

            // then create transaction
            $transaction = Transaction::create([
                'customer_id' => $customer->id,
                'status'      => 'checkout'
            ]);
            
            $singleProduct = Product::where('slug',$request->slug)->first();
            
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
            
            $privateKey = getenv('TRIPAY_PRIVATE_KEY');
            $merchantCode = getenv('TRIPAY_MERCHANT_CODE');
            $merchantRef = strtotime('now').'-'.$transaction->id; // getenv('TRIPAY_MERCHANT_REF'); Kode Unik Transaksi
            
            $signature = hash_hmac('sha256', $merchantCode.$merchantRef.$all_total_price, $privateKey);

            $data = [
                'method'            => $request->pg,
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
                return response()->json($response,400);
            }
            $response_data = $response['data'];
            $payments = [
                'transaction_id' => $transaction->id,
                'total' => $all_total_price,
                'admin_fee' => $payment['total_fee']['flat'],
                'checkout_url' => $response_data['checkout_url'],
                'payment_type' => $request->pg,
                'merchant_ref'      => $merchantRef,
                'status' => $response_data['status'],
                'payment_reference' => $response_data['reference'],
                'payment_code' => $response_data['pay_code'],
                'expired_time' => $response_data['expired_time'],
            ];
            $_payment = Payment::create($payments);

            DB::commit();

            if(env('WA_BLAST_URL') !== null && env('WA_BLAST_URL') !== ''):

                $total = $all_total_price;

                $total += $payment['total_fee']['flat'];

                $notifAction = new NotifAction;
                $message = $notifAction->checkoutWASuccess($transaction, $total, $customer, $_payment, $order_items_string);
                WaBlast::webisnisSend($request->sender, $request->phone, $message);

            endif;

            // return redirect()->to($response_data['checkout_url']);
            WaBlast::webisnisSend($request->sender, $request->phone, "Silahkan klik link berikut untuk menyelesaikan pembayaran ".$response_data['checkout_url']);
            return response()->json([
                'status' => 'succes',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }

            
    }

    function regTiket(Request $request)
    {
        $phone = str_replace('+','',$request->phone);
        $options = [
            'hut4-free',
            'hut4-35k'
        ];
        $user = User::where('email',$request->phone)->first();
        if(!$user->alumni)
        {
            WaBlast::webisnisSend($request->sender, $phone, "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut.");
            return response()->json([
                'status' => 'fail',
                'message' => "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut."
            ], 400);
        }

        if(strpos($request->option,"#") !== false)
        {
            $option = explode('#',$request->option); // 0 = slug, 1 = option index, 2 = pg index
            $paymentChannel = (array) $this->paymentChannel();
            $payments = $paymentChannel['data'];
            $payment  = false;
            $paymentChannel = array_map(function($p){ return $p['code']; }, $paymentChannel['data']);
            $order_items_string = "";
            if($option[2] == count($payments))
            {
                $payment = 'cash';
            }
            else
            {
                $pgIndex = $option[2]-1;
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

                $custData = [
                    'user_id' => $user->id,
                    'first_name' => $user->alumni->name,
                    'last_name' => ' ',
                    'email' => strtotime('now').'@randomuser.com',
                    'phone_number' => $phone,
                ];
                $customer_valid = false;
                if($user->alumni->email && filter_var($user->alumni->email, FILTER_VALIDATE_EMAIL)) 
                {
                    $customer = Customer::where('email',$user->alumni->email);
                    if($customer->exists())
                    {
                        $customer = $customer->first();
                        $customer_valid = true;
                    }
                }
                
                if(!$customer_valid)
                {
                    // create customer first
                    $customer = Customer::create($custData);
                }
                

                // then create transaction
                $transaction = Transaction::create([
                    'customer_id' => $customer->id,
                    'status'      => 'checkout'
                ]);
                
                $singleProduct = Product::where('slug',$options[$option[1]-1])->first();
                
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

                if(env('WA_BLAST_URL') !== null && env('WA_BLAST_URL') !== ''):

                    $total = $all_total_price;

                    if(is_array($payment)) $total += $payment['total_fee']['flat'];

                    $notifAction = new NotifAction;
                    $message = $notifAction->checkoutWASuccess($transaction, $total, $customer, $_payment, $order_items_string);
                    WaBlast::webisnisSend($request->sender, $phone, $message);

                endif;

                // return redirect()->to($response_data['checkout_url']);
                WaBlast::webisnisSend($request->sender, $phone, "Silahkan klik link berikut untuk menyelesaikan pembayaran ".$response_data['checkout_url']);
                return response()->json([
                    'status' => 'succes',
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
        }
        else
        {
    
            $index = $request->option-1;
    
            if(!isset($options[$index]))
            {
                WaBlast::webisnisSend($request->sender, $request->phone, 'Maaf! Pilihan yang anda pilih tidak valid. Silahkan ulangi pendaftaran.');
                return response()->json([
                    'status' => 'failed',
                    'errors' => $error
                ], 400);
            }
    
            $slug = $options[$index];
            $singleProduct = Product::where('slug',$slug)->first();
    
            if($singleProduct->price == 0)
            {
                DB::beginTransaction();
                try {
                    $user = User::where('email',$request->phone)->first();
        
                    if(!$user->alumni)
                    {
                        WaBlast::webisnisSend($request->sender, $phone, "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut.");
                        return response()->json([
                            'status' => 'fail',
                            'message' => "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut."
                        ], 400);
                    }
        
                    $customer = Customer::where('user_id',$user->id);
        
                    if(!$customer->exists())
                    {
                        $customer = Customer::create([
                            'user_id' => $user->id,
                            'first_name' => $user->name,
                            'last_name' => ' ',
                            'email' => $user->email,
                            'phone_number' => $phone,
                        ]);
                    }
                    else
                    {
                        $customer = $customer->first();
                    }
        
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
        
                    if(!$singleProduct->stock_status && $singleProduct->stock == 0)
                    {
                        WaBlast::webisnisSend($request->sender, $phone, "Maaf, saat ini tiket sudah sold out atau tidak tersedia.");
                        return response()->json([
                            'status' => 'fail',
                            'message' => "Maaf, saat ini tiket sudah sold out atau tidak tersedia."
                        ], 400);
                    }
        
                    // then create transaction
                    $transaction = Transaction::create([
                        'customer_id' => $customer->id,
                        'status'      => 'checkout'
                    ]);
                    
                    $transaction_item = TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id'     => $singleProduct->id,
                        'amount'         => 1,
                        'total'          => $singleProduct->price,
                        'notes'          => ' '
                    ]);
        
                    DB::commit();
        
                    if(env('WA_BLAST_URL') !== null && env('WA_BLAST_URL') !== ''):
        
                        $notifAction = new NotifAction;
                        $message = $notifAction->regticketSuccess($singleProduct, $user->alumni);
                        WaBlast::webisnisSend($request->sender, $phone, $message);
        
                    endif;
        
                    // return redirect()->to($response_data['checkout_url']);
                    return response()->json([
                        'status' => 'succes',
                    ]);
                } catch (\Throwable $th) {
                    DB::rollback();
                    throw $th;
                }
            }
            else
            {
                $paymentChannel = (array) $this->paymentChannel();
                $payments = $paymentChannel['data'];
                // $paymentChannel = array_map(function($p){ return $p['code']; }, $paymentChannel['data']);
                $message = "*Silahkan Pilih Metode Pembayaran :*
";
                foreach($payments as $i => $p)
                {
$message .= ($i+1).'. '.$p['code']."
";
                }
$message .= ($i+1).'. CASH';
                // $paymentChannel = implode(',',$paymentChannel);
                WaBlast::webisnisSend($request->sender, $phone, $message);
                return response()->json([
                    'status' => 'succes',
                ]);
            }
        }

    }

    function regTiketOption(Request $request)
    {
        // validation
        $validator = Validator::make($request->all(), [
          'phone' => 'required|exists:users,email'
        ], 
        [
            'phone.exists' => 'Maaf, pendaftaran HUT4 IKARHOLAZ ditolak, no WA anda belum terdaftar di NRA System. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut.'
        ]);
        
        if ($validator->fails()) {
            $error =  $validator->getMessageBag()->first();
            WaBlast::webisnisSend($request->sender, $request->phone, $error);
            return response()->json([
                'status' => 'failed',
                'errors' => $error
            ], 400);
        }

        DB::beginTransaction();
        try {
            $phone = str_replace('+','',$request->phone);
            $user = User::where('email',$request->phone)->first();

            if(!$user->alumni)
            {
                WaBlast::webisnisSend($request->sender, $phone, "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut.");
                return response()->json([
                    'status' => 'fail',
                    'message' => "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut."
                ], 400);
            }

            
            WaBlast::webisnisSend($request->sender, $phone, "*Silahkan pilih salah satu :*
1. Bawa makanan sendiri (FREE)
2. Bayar 35k");

            return response()->json([
                'status' => 'succes',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    function cekNra(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,email'
        ], 
        [
            'phone.exists' => 'Maaf, no WA anda belum terdaftar di NRA System. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut.'
        ]);
        
        if ($validator->fails()) {
            $error =  $validator->getMessageBag()->first();
            // WaBlast::webisnisSend($request->sender, $request->phone, $error);
            return response()->json([
                'status' => 'failed',
                'errors' => $error
            ], 400);
        }

        $phone = str_replace('+','',$request->phone);
        $user = User::where('email',$request->phone)->first();

        if(!$user->alumni)
        {
            // WaBlast::webisnisSend($request->sender, $phone, "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut.");
            return response()->json([
                'status' => 'failed',
                'message' => "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut."
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $user->alumni
        ]);
    }

    function sendCandidates(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users,email'
        ], 
        [
            'phone.exists' => 'Maaf, no WA anda belum terdaftar di NRA System. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut.'
        ]);
        
        if ($validator->fails()) {
            $error =  $validator->getMessageBag()->first();
            WaBlast::webisnisSend($request->sender, $request->phone, $error);
            return response()->json([
                'status' => 'failed',
                'errors' => $error
            ], 400);
        }

        $phone = str_replace('+','',$request->phone);
        $user = User::where('email',$request->phone)->first();

        if(!$user->alumni)
        {
            WaBlast::webisnisSend($request->sender, $phone, "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut.");
            return response()->json([
                'status' => 'failed',
                'message' => "Maaf, tidak ada data alumni dengan nomor WA Anda. Lakukan pendaftaran Alumni melalui kanal tersedia, atau hubungi mimin untuk bantuan lebih lanjut."
            ], 400);
        }
        
        WaBlast::webisnisSend($request->sender, $phone, "Berikut adalah data kandidat voting. Silahkan pilih sesuai dengan nomor urut
".$request->data);
        return response()->json([
            'status' => 'success',
            'data'   => $user->alumni
        ]);
    }
}
