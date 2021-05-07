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

        $wa = new Fonnte;
        $message = "Terima kasih $contact->nama_pendaftar ($contact->alamat) telah melakukan pembayaran PPDB Malhikdua melalui $contact->tipe_pembayaran";
        $message .= "\nBerikut adalah tiket pengisian formulir anda : $tiket";
        $message .= "\nGunakan tiket ini untuk mengisi/mengedit formulir PPDB hingga lengkap.";
        $message .= "\nFormulir PPDB di ".route('login')." (ONLINE)";
        $message .= "\nManfaatkan tombol SAVE untuk menyimpan isian formulir.";
        $message .= "\nJika sudah, klik tombol VERIFIKASI BERKAS/PENDAFTARAN untuk diperiksa petugas.";
        $wa->send_text("62".$contact->no_wa,$message);

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
