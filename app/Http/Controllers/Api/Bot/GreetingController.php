<?php

namespace App\Http\Controllers\Api\Bot;

use App\Models\User;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class GreetingController extends Controller
{
    //
    public function cekNra(Request $request)
    {
        $phone = str_replace('+','',$request->phone);
        $user  = User::where('email','LIKE', '%'.$phone.'%')->first();
        $message = 'Nomer WA anda '.$phone.' tidak terdaftar dalam sistem NRA IKARHOLAZ. Anda harus menggunakan no WA terdaftar saat menggunakan layanan ini.

Hubungi mimin untuk memastikan/mengubah nomer WA yang terdaftar di sistem NRA IKARHOLAZ.

Demi keamanan: Permintaan ganti nomer tidak bisa diwakilkan. 1 nama alumni berlaku 1 NRA dan 1 nomer HP

Jika anda belum melakukan pendaftaran anggota IKARHOLAZ/belum memiliki NRA, gunakan layanan pendaftaran anggota IKARHOLAZ melalui WA, lebih simpel dan praktis. Caranya, ketik:

REG nama#kelas#tahunmasuk#tahunlulus#alamat

Alternatif lain melalui:
web: https://gerai.ikarholaz.id/register';

        if(!empty($user))
        {
            $message = "Nomor WA anda sudah terdaftar sebagai ".$user->alumni->name;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'message' => $message
            ]
        ]);
    }

    public function validateNra(Request $request)
    {
        $nras = $request->nras;
        $nras = explode(',', $nras);
        $message = 'Terdapat NRA yang tidak valid';

        $alumnis = \App\Models\Alumni::whereIn('nra', $nras);
        $message = $alumnis->exists() && $alumnis->count() == count($nras) ? 'nra valid' : $message;

        return response()->json([
            'status' => 'success',
            'data'   => [
                'message' => $message
            ]
        ]);
    }

    public function getUser(Request $request)
    {
        $phone = str_replace('+','',$request->phone);
        $user  = User::where('email','LIKE', '%'.$phone.'%')->with('alumni')->first();
        $nra   = '';
        $tahunLulus   = '';
        $nama = '';
        $status = 'fail';
        $message = 'Nomer WA anda '.$phone.' tidak terdaftar dalam sistem NRA IKARHOLAZ. Anda harus menggunakan no WA terdaftar saat menggunakan layanan ini.

Hubungi mimin untuk memastikan/mengubah nomer WA yang terdaftar di sistem NRA IKARHOLAZ.

Demi keamanan: Permintaan ganti nomer tidak bisa diwakilkan. 1 nama alumni berlaku 1 NRA dan 1 nomer HP

Jika anda belum melakukan pendaftaran anggota IKARHOLAZ/belum memiliki NRA, gunakan layanan pendaftaran anggota IKARHOLAZ melalui WA, lebih simpel dan praktis. Caranya, ketik:

REG nama#kelas#tahunmasuk#tahunlulus#alamat

Alternatif lain melalui:
web: https://gerai.ikarholaz.id/register';

        if(!empty($user))
        {
            $status = 'success';
            $nama = $user->alumni->name;
            $message = "Nomor WA anda sudah terdaftar sebagai ".$user->alumni->name;
            $nra = $user->alumni->NRA;
            $tahunLulus   = $user->alumni->graduation_year;
        }

        return response()->json([
            'status' => $status,
            'data'   => [
                'phone' => $phone,
                'nama'  => $nama,
                'nra' => $nra,
                'tahun_lulus' => $tahunLulus,
                'message' => $message
            ]
        ]);
    }

    function createPayment(Request $request)
    {
        try {
            $privateKey = getenv('TRIPAY_PRIVATE_KEY');
            $merchantCode = 'T30083';
            $merchantRef = 'trx-greetings-'.strtotime('now'); // getenv('TRIPAY_MERCHANT_REF'); Kode Unik Transaksi
            $all_total_price = $request->nominal;
            
            $signature = hash_hmac('sha256', $merchantCode.$merchantRef.$all_total_price, $privateKey);
            $paymentMethod = $request->bank == 'QRIS' ? 'QRIS2' : $request->bank.'VA';

            $data = [
                'method'            => $paymentMethod,
                'merchant_ref'      => $merchantRef,
                'amount'            => $all_total_price,
                'customer_name'     => 'trx-customer-'.$request->number,
                'customer_email'    => $merchantRef.'@mail.com',
                'customer_phone'    => $request->number,
                'callback_url'      => route('tripay-bot-greetings-callback'),
                'order_items'       => [
                    [
                        'sku'       => $merchantRef,
                        'name'      => 'bot-greetings-donation',
                        'price'     => $all_total_price,
                        'quantity'  => 1
                    ]
                ],
                'signature'         => $signature
            ];
            
            $tripay = new \App\Models\Ref\Tripay($privateKey, getenv('TRIPAY_API_KEY'));
            $response = $tripay->curlAPI($tripay->URL_transMp,$data,'POST');
            if($response['success'])
            {
                $responseData = $response['data'];
                return $responseData;
            }
            else
            {
                return $response;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
        

        return [
            'message' => 'unavailable error'
        ];
    }

    function generateTicketHalbil(Request $request)
    {
        \Log::info($request->all());
        $phone = str_replace('+','',$request->buyer);
        $buyer  = User::where('email','LIKE', '%'.$phone.'%')->first();

        $product = $request->product;
        $participant = $request->participant;
        $participants = [];
        $qrcode = [];
        $lastTicket = (int) $request->last_ticket;

        if($product == 'halbil_kolektif')
        {
            if(strpos($participant, ',') !== false)
            {
                $nras = explode(',', $participant);
                
                foreach($nras as $part)
                {
                    $lastTicket += 1;
                    $qr_content = $part.',C'.str_pad($lastTicket, 3, "0", STR_PAD_LEFT );
                    $part = trim($part);
                    $part = explode(' ', $part);;
                    $qr_content = urlencode($qr_content);
                    $participants[] = [
                        'name' => $part[0],
                        'graduation_year' => isset($part[1]) ? $part[1] : '',
                    ];
                    $barcode = file_get_contents("https://qrcode.tec-it.com/API/QRCode?data=".$qr_content);
                    $qrcode[] = 'data:image/png;base64,' . base64_encode($barcode);
                }
            }
            else
            {
                $lastTicket += 1;
                $qr_content = $participant.',C'.str_pad($lastTicket, 3, "0", STR_PAD_LEFT );
                $part = trim($participant);
                $part = explode(' ', $participant);
                $qr_content = urlencode($qr_content);
                $participants[] = [
                    'name' => $part[0],
                    'graduation_year' => isset($part[1]) ? $part[1] : '',
                ];
                $barcode = file_get_contents("https://qrcode.tec-it.com/API/QRCode?data=".$qr_content);
                $qrcode[] = 'data:image/png;base64,' . base64_encode($barcode);
            }
        }
        else
        {
            $qr_content = $buyer->alumni->NRA.','.$buyer->alumni->name.',C'.str_pad(($lastTicket+1), 3, "0", STR_PAD_LEFT );
            $qr_content = urlencode($qr_content);
            $participants[] = [
                'name' => $buyer->alumni->name,
                'graduation_year' => $buyer->alumni->graduation_year
            ];
            $barcode = file_get_contents("https://qrcode.tec-it.com/API/QRCode?data=".$qr_content);
            $qrcode[] = 'data:image/png;base64,' . base64_encode($barcode);
        }

        $lastTicket = (int) $request->last_ticket;

        $path = public_path('/assets/images/e-tiket-bg.jpeg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $bg   = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $content = view('pdf.halbil-ticket',compact('participants','qrcode','lastTicket','bg'))->render();

        $pdf = PDF::loadHTML($content)->setOptions(['defaultFont' => 'Courier'])->setPaper([0,0,440,580]);
        // ->stream('download.pdf');
        $filename = request('filename', md5(md5($buyer->id.".".(strtotime('now')))));
        $file_to_save = 'pdf/'.$filename.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->output());

        return response()->json([
            'data' => [
                'url' => url($file_to_save),
                'name' => $buyer->alumni->name,
                'tahun_lulus' => $buyer->alumni->graduation_year
            ]
        ]);
    }
}
