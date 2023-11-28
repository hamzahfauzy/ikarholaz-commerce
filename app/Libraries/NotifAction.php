<?php
namespace App\Libraries;

use App\Models\WaBlast;

class NotifAction
{

    public function checkoutSuccess($cart, $transaction, $total, $customer, $payment, $order_items_string = "")
    {
        $cart_name = ($cart->parent?$cart->parent->parent->name.' - ':'').$cart->name;
        if(($cart->parent && $cart->parent->parent->categories->contains(config('reference.event_kategori'))) ||  $cart->categories->contains(config('reference.event_kategori')))
        {
            $item          = $transaction->transactionItems[0];
            $product       = $item->product;
            $custom_fields = \App\Models\CustomField::where('class_target','App\Models\EventProduct')->get();
            $cf_product_id = $product->parent ? $product->parent->parent->id : $product->id;
            $cf = [];
            foreach($custom_fields as $key => $value)
            {
                $cf[$value->field_key] = $value->get_value($cf_product_id)->field_value;
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
    
Kode Transaksi: *$transaction->id*
Metode Pembayaran: $payment->payment_type ".($payment->payment_type == 'cash' ? "(Hubungi mimin untuk info/panduan pembayaran CASH)" : $payment->payment_code)."
Nama Pemesan: $customer->full_name
Acara: ".($product->parent?$product->parent->parent->name.' - ':'').$product->name."
Tempat: $cf[venue]
Waktu: $cf[waktu]
    
Daftar peserta
$part
    
Biaya : Rp. ".number_format($total)."
    
Lakukan pembayaran sebesar Rp. ".number_format($total)." melalui metode pembayaran yang dipilih saat transaksi atau abaikan jika GRATIS. QRCODE akan dikirim setelah petugas menyetujui transaksi ini.

Terima kasih,
Salam hangat
_Mimin Gerai_

---------
Jika ada pertanyaan silakan hubungi langsung di inbox@ikarholaz.com atau di +62 838-0661-1212

*GERAI IKARHOLAZ*
_part of Sistem Informasi Rholaz (SIR) 2022_";
        }
        else if(($cart->parent && $cart->parent->parent->categories->contains(config('reference.voucher_kategori'))) ||  $cart->categories->contains(config('reference.voucher_kategori')))
        {
            $message = "Hai kak $customer->full_name,
Terima kasih telah melakukan transaksi di Gerai IKARHOLAZ dengan rincian sbb:
    
Kode Transaksi: $transaction->id
Metode Pembayaran: $payment->payment_type ".($payment->payment_type == 'cash' ? "(Hubungi mimin untuk info/panduan pembayaran CASH)" : $payment->payment_code)."
Nama Anda: $customer->full_name
Email: $customer->email
Nomor HP: $customer->phone_number

Rincian transaksi
$order_items_string
    
TOTAL : Rp. ".number_format($total)."
    
Silahkan lakukan pembayaran sesuai metode yang dipilih. 
    
*Khusus transfer manual/cash lakukan konfirmasi dengan mereplay notifikasi ini*

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
    
TOTAL : Rp. ".number_format($total)."
    
Silahkan lakukan pembayaran sesuai metode yang dipilih. 
    
*Khusus transfer manual/cash lakukan konfirmasi dengan mereplay notifikasi ini.";
        }
            
        WaBlast::send($customer->phone_number,$message);
    }
    
    public function checkoutWASuccess($transaction, $total, $customer, $payment, $order_items_string = "")
    {
        $item          = $transaction->transactionItems[0];
        $product       = $item->product;
        $custom_fields = \App\Models\CustomField::where('class_target','App\Models\EventProduct')->get();
        $cf_product_id = $product->parent ? $product->parent->parent->id : $product->id;
        $cf = [];
        foreach($custom_fields as $key => $value)
        {
            $cf[$value->field_key] = $value->get_value($cf_product_id)->field_value;
        }
            
            $message = "Hai kak $customer->full_name,
Terima kasih telah melakukan transaksi di Gerai IKARHOLAZ dengan rincian sbb:
    
Kode Transaksi: $transaction->id
Metode Pembayaran: $payment->payment_type  $payment->payment_code
Nama Pemesan: $customer->full_name
Acara: ".($product->parent?$product->parent->parent->name.' - ':'').$product->name."
Tempat: $cf[venue]
Waktu: $cf[waktu]
    
Daftar peserta
$customer->full_name
    
Biaya : Rp. ".number_format($total)."
    
Lakukan pembayaran sebesar Rp. ".number_format($total)." melalui metode pembayaran yang dipilih saat transaksi atau abaikan jika GRATIS. QRCODE akan dikirim setelah petugas menyetujui transaksi ini.

Terima kasih,
Salam hangat
_Mimin Gerai_

---------
Jika ada pertanyaan silakan hubungi langsung di inbox@ikarholaz.com atau di +62 838-0661-1212

*GERAI IKARHOLAZ*
_part of Sistem Informasi Rholaz (SIR) 2022_";
            
        return $message;
    }

    public function checkoutVoucherWASuccess($transaction, $total, $customer, $payment, $order_items_string = "")
    {
            
        $message = "Hai kak $customer->full_name,
Terima kasih telah melakukan transaksi di Gerai IKARHOLAZ dengan rincian sbb:
    
Kode Transaksi: $transaction->id
Metode Pembayaran: $payment->payment_type ".($payment->payment_type == 'cash' ? "(Hubungi mimin untuk info/panduan pembayaran CASH)" : $payment->payment_code)."
Nama Anda: $customer->full_name
Email: $customer->email
Nomor HP: $customer->phone_number

Rincian transaksi
$order_items_string
    
TOTAL : Rp. ".number_format($total)."
    
Silahkan lakukan pembayaran sesuai metode yang dipilih. 
    
*Khusus transfer manual/cash lakukan konfirmasi dengan mereplay notifikasi ini*

Terima kasih,
Salam hangat
_Mimin Gerai_

---------
Jika ada pertanyaan silakan hubungi langsung di inbox@ikarholaz.com atau di +62 838-0661-1212

*GERAI IKARHOLAZ*
_part of Sistem Informasi Rholaz (SIR) 2022_";
            
        return $message;
    }
    
    public function paymentSuccess($product, $customer, $transaction, $payment, $return_string = false)
    {
        $file_url = false;
        if(($product->parent && $product->parent->parent->categories->contains(config('reference.event_kategori'))) || $product->categories->contains(config('reference.event_kategori')))
        {
            $pdf_url = (new \App\Libraries\PdfAction)->ticketUrl($transaction->id);

            $message = "Hai kak $customer->full_name,
Transaksi Etiket Anda dengan kode booking *#$transaction->id* telah disetujui.

Silakan download E-TIKET nya melalui ".url()->to($pdf_url)." 

Terima kasih,
Salam hangat
_Mimin Gerai_

---------
Jika ada pertanyaan silakan hubungi langsung di inbox@ikarholaz.com atau di +62 838-0661-1212

*GERAI IKARHOLAZ*
_part of Sistem Informasi Rholaz (SIR) 2022_";
        }
        else if(($product->parent && $product->parent->parent->categories->contains(config('reference.voucher_kategori'))) || $product->categories->contains(config('reference.voucher_kategori')))
        {
            $pdf_url = (new \App\Libraries\PdfAction)->voucherUrl($transaction->id);
            // $file_url = url()->to($pdf_url);

            $message = "Hai kak $customer->full_name,
Terima kasih telah melakukan pembayaran untuk kode transaksi *#$transaction->id* sebesar Rp. $transaction->total_formated melalui $payment->payment_type.

Silakan download e-Voucher nya melalui ".url()->to($pdf_url)."

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
        if($return_string){
            if($file_url)
            {
                WaBlast::send($customer->phone_number,'Lampiran',$file_url);
            }
            return $message;
        }
        WaBlast::send($customer->phone_number,$message,$file_url);
    }

    function regticketSuccess($event, $alumni, $transaction = false)
    {
        if(in_array($event->id,[121]))
        {
            return 'Terima kasih Nama: *'.$alumni->name.'* - NRA: *'.$alumni->NRA.'* telah melakukan pendaftaran Event '.$event->name.' - BAWA SENDIRI BERKATAN. Pendaftaran telah kami setujui dengan kode booking: *['.$transaction->id.']*. Saat ke lokasi WAJIB Bawa Sendiri Berkatan dengan kemasan box warna PUTIH berisi:
1. NASI PUTIH
2. AYAM BAKAR
3. URAP-URAP
4. PEYEK
5. LEMPER
6. PUKIS
7. AIR MINERAL 600 ml

Sampai ketemu di lokasi ya kak! Mimin pake baju pink.

Terima kasih,
Salam hangat
Mimin Gerai

---------
Jika ada pertanyaan silakan hubungi langsung di inbox@ikarholaz.com atau di +62 838-0661-1212

*GERAI IKARHOLAZ*
_part of Sistem Informasi Rholaz (SIR) 2022_';
        }
        return "Terima kasih telah melakukan pendaftaran Event $event->name. Pendaftaran atas Nama: *$alumni->name* - NRA: *$alumni->NRA* - Alumni: $alumni->graduation_year akan kami verifikasi berdasar syarat dan ketentuan berlaku.";
    }

    public function paymentCashSuccess($product, $customer, $transaction)
    {
        $file_url = false;
        if(($product->parent && $product->parent->parent->categories->contains(config('reference.event_kategori'))) || $product->categories->contains(config('reference.event_kategori')))
        {
            $pdf_url = (new \App\Libraries\PdfAction)->ticketUrl($transaction->id);
            $message = "Hai kak ".$customer->full_name.",
Terima kasih telah melakukan pembayaran untuk kode booking #".$transaction->id." sebesar Rp. ".$transaction->total_formated." melalui cash.

Silakan download E-TIKET nya melalui ".url()->to($pdf_url)." 
Sampai ketemu di lokasi ya kak! Mimin pake baju pink.

Terima kasih,
Salam hangat
Mimin Gerai

---------
Jika ada pertanyaan silakan hubungi langsung di inbox@ikarholaz.com atau di +62 838-0661-1212

*GERAI IKARHOLAZ*
_part of Sistem Informasi Rholaz (SIR) 2022_";

        }
        else if(($product->parent && $product->parent->parent->categories->contains(config('reference.voucher_kategori'))) || $product->categories->contains(config('reference.voucher_kategori')))
        {
            $pdf_url = (new \App\Libraries\PdfAction)->voucherUrl($transaction->id);
            $file_url = url()->to($pdf_url);

            $message = "Hai kak $customer->full_name,
Terima kasih telah melakukan pembayaran untuk kode transaksi *#$transaction->id* sebesar Rp. $transaction->total_formated telah kami terima.

Silakan download e-Voucher nya melalui ".url()->to($pdf_url)."

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
Pembayaran atas tagihan #$transaction->id sebesar ".$transaction->total_formated." telah kami terima.
            
Pesanan kakak segera kami proses ya
Terima kasih.";
        }
        WaBlast::send($customer->phone_number,$message,$file_url);
    }
}