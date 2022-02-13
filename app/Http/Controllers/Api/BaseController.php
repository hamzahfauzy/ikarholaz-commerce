<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\Cart;
use App\Models\Price;
use App\Models\WaBlast;
use App\Models\Ref\Tripay;
use App\Models\Ref\District;
use Illuminate\Http\Request;
use App\Models\Ref\ShippingRates;
use App\Http\Controllers\Controller;
use App\Models\Ref\Province;
use Barryvdh\DomPDF\Facade\Pdf;

class BaseController extends Controller
{
    //
    function getProvinces()
    {
        return Province::get();
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
        $file_to_save = 'pdf/'.$request->NRA.'.pdf';
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
        $content = "<html><body><div style='padding-top:140px;position:realtive;width:400px;height:500px;margin:auto;'><img src=\"$base64\" style='position:absolute;top:40px;z-index:-1;width:400px;height:500px;object-fit:contain;' />";
        $content .= "<table border='1' cellpadding='5' cellspacing='0' width='400px' align='center'>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'><h2>KPU IKARHOLAZ</h2></td>";
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
        $content .= "<td style='text-align:center'>TELAH MEMILIH : ".$request->candidate_name."</td>";
        $content .= "</tr>";
        $content .= "<tr>";
        $content .= "<td style='text-align:center'>TANGGAL DAN WAKTU MEMILIH : ".$request->created_at."</td>";
        $content .= "</tr>";
        $content .= "</table></div></body></html>";
        $pdf = PDF::loadHTML($content);
        $file_to_save = 'pdf/'.$request->NRA.'.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdf->output()); 
        $alumni = Alumni::where('NRA',$request->NRA)->first();
        $message = "$request->name, $request->NRA telah menggunakan hak suara dengan memilih $request->candidate_name sebagai ketua umum IKARHOLAZ periode 2021-2024. Berikut adalah bukti surat suara Anda

*Mohon tidak menghapus notifikasi sampai program Munas berakhir sebagai bukti valid partisipasi anda.*";

        return WaBlast::send($alumni->user->email, $message);
        return WaBlast::sendfile($alumni->user->email, $message, asset($file_to_save));
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
}
