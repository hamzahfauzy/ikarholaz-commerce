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
}
