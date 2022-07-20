<?php
namespace App\Libraries;

use App\Models\WaBlast;

class NotifAction
{

    public function checkoutSuccess($cart, $transaction, $total, $customer, $payment, $order_items_string = "")
    {
        $cart_name = ($cart->parent?$cart->parent->parent->name.' - ':'').$cart->name;
        if($cart->categories->contains(config('reference.event_kategori')))
        {
            $item          = $transaction->transactionItems[0];
            $product       = $item->product;
            $custom_fields = \App\Models\CustomField::where('class_target','App\Models\EventProduct')->get();
            $cf = [];
            foreach($custom_fields as $key => $value)
            {
                $cf[$value->field_key] = $value->get_value($product->id)->field_value;
            }
            
            $participant_custom_fields = \App\Models\CustomField::where('class_target','App\Models\Event')->get();
            $participants = [];
            foreach($participant_custom_fields as $key => $value)
            {
                $cf_values = $value->customFieldValues()->where('pk_id',$item->id)->get();
                foreach($cf_values as $cf_value)
                {
                    $participants[$key][] = $cf_value->field_value;
                }
            }

            $flip = array_map(null, ...$participants);
            $part = "";
            foreach($flip as $ps)
            {
                $part .= implode(',',$ps);
                $part .= "\n";
            }

            $message = "Hai kak $customer->full_name,
Terima kasih telah melakukan transaksi di Gerai IKARHOLAZ dengan rincian sbb:
    
Kode Transaksi: $transaction->id
Metode Pembayaran: $payment->payment_type ".($payment->payment_type == 'cash' ? "(Hubungi mimin untuk info/panduan pembayaran CASH)" : $payment->payment_code)."
Nama Pemesan: $customer->full_name
Acara: $product->name
Tempat: $cf[venue]
Waktu: $cf[waktu]
    
Daftar peserta
$part
    
Biaya : Rp. ".number_format($total)."
    
Saat ini status pemesanan kakak masih PENDING hingga melakukan pembayaran sesuai jumlah tersebut melalui metode pembayaran yang dipilih saat transaksi.

Terima kasih,
Salam hangat
_Mimin Gerai_

---------
Jika ada pertanyaan silakan hubungi langsung di inbox@ikarholaz.com atau di +62 838-0661-1212

*GERAI IKARHOLAZ*
_part of Sistem Informasi Rholaz (SIR) 2022_";
        }
        else
        {
            $message = "Terima kasih sudah melakukan transaksi di IKARHOLAZ. Berikut adalah detail transaksi Anda:
    
Kode Transaksi: $transaction->id
Metode Pembayaran: $payment->payment_type ".($payment->payment_type == 'cash' ? "(Hubungi mimin untuk info/panduan pembayaran CASH)" : $payment->payment_code)."
Nama Anda: $customer->full_name
Email: $customer->email
Nomor HP: $customer->phone_number
Alamat pengiriman: $customer->address
    
Rincian transaksi
$order_items_string
    
TOTAL : ".number_format($total)."
    
Silahkan lakukan pembayaran sesuai metode yang dipilih. 
    
*Khusus transfer manual/cash lakukan konfirmasi dengan mereplay notifikasi ini.";
        }
            
        WaBlast::send($customer->phone_number,$message);
    }
    
    public function paymentSuccess($product, $customer, $transaction, $payment)
    {
        if($product->categories->contains(config('reference.event_kategori')))
        {
            $pdf_url = (new \App\Libraries\PdfAction)->ticketUrl($transaction->id);

            $message = "Hai kak $customer->full_name,
Terima kasih telah melakukan pembayaran untuk kode booking *#$transaction->id* sebesar Rp. $transaction->total_formated melalui $payment->payment_type.

Silakan download E-TIKET nya melalui ".url()->to($pdf_url)." 
Sampai ketemu di lokasi ya kak! Mimin pake baju pink.

Terima kasih,
Salam hangat
_Mimin Gerai_

---------
Jika ada pertanyaan silakan hubungi langsung di inbox@ikarholaz.com atau di +62 838-0661-1212

*GERAI IKARHOLAZ*
_part of Sistem Informasi Rholaz (SIR) 2022_";
        }
        else
        {
            $message = "Terima Kasih Kak ".$customer->full_name."
Pembayaran atas tagihan #$payment->transaction_id sebesar ".$payment->total_formated." telah kami terima.
            
Pesanan kakak segera kami proses ya
Terima kasih.";
        }
        WaBlast::send($customer->phone_number,$message);
    }
}