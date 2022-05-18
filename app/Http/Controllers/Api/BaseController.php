<?php

namespace App\Http\Controllers\Api;

use App\Models\Card;
use App\Models\Cart;
use App\Models\Price;
use App\Models\Alumni;
use App\Models\WaBlast;
use App\Models\Ref\Tripay;
use App\Models\Ref\District;
use App\Models\Ref\Province;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Ref\ShippingRates;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    //
    function getProvinces()
    {
        return Province::get();
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
}
