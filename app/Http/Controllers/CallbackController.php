<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Ref\Tripay;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    function tiket($contact)
    {
        if($contact->tiket == "")
        {
            $tiket = $contact->id;
            if($tiket < 10)
                $tiket = "000".$tiket;
            elseif($tiket < 100)
                $tiket = "00".$tiket;
            elseif($tiket < 1000)
                $tiket = "0".$tiket;
            
            $tiket = "MDTKT".$tiket;
            $tiket = md5($tiket);
            $tiket = substr($tiket,0,8);
        }
        else
            $tiket = $contact->tiket;

        $message = "Terima kasih telah melakukan pembayaran sebesar Rp.$contact->biaya_pembayaran atas tagihan [kode transaksi].";
        $message .= "\nInfo pengiriman:";
        $message .= "\nNama: $contact->nama_pendaftar";
        $message .= "\nNomor HP: $contact->no_wa";
        $message .= "\nAlamat pengiriman: $contact->alamat";
        $message .= "\nCek kembali alamat pengiriman, jika ada kesalahan lakukan revisi alamat dengan membalas notifikasi ini.";

        $message .= "\n*Transaksi akan diproses mengikuti ketentuan atas produk yang dibeli.";
        WaBlast::send("+62".$contact->no_wa,$message);

        return $tiket;
    }
    //
    function tripay()
    {
        $privateKey = getenv('TRIPAY_PRIVATE_KEY');
        $apiKey = getenv('TRIPAY_API_KEY');
        $tripay = new Tripay($privateKey, $apiKey);
        $callback = $tripay->callback();

        if($callback->status)
        {
            $merchantRef = $callback->reference;
            $payment = Payment::where("merchant_ref",$merchantRef)->firstOrFail();
            $data = [
                'status' => $callback->status,
            ];
            $payment->update($data);
            $payment->transaction()->update($data);
            if($callback->status == "PAID")
            {
                foreach($payment->transaction->transaction_items as $item)
                {
                    if($item->product->custom_fields)
                    {
                        $cart = $item->product;
                        $card_number = '';
                        foreach($cart->custom_fields as $cf)
                        {
                            if($cf->customField->field_key == 'nomor_kartu')
                                $card_number = $cf->field_value;
                        }
                        $card = Card::where('card_number',$card_number)->first();
                        $card->update([
                            'status' => 'Active'
                        ]);
                    }
                }
                
                $message = "Terima Kasih Kak ".$payment->transaction->customer->full_name."
Pembayaran atas tagihan #$payement->transaction_id sebesar ".$payment->total_formated." telah kami terima.
            
Pesanan kakak segera kami proses ya
Terima kasih.";
                WaBlast::send($payment->transaction->customer->phone_number,$message);
            }
            
            return ['success'=>true];
        }
    }
}
