<?php
namespace App\Libraries;

use App\Models\TransactionItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PdfAction
{
    function ticketUrl($transaction_id)
    {
        $transaction = \App\Models\Transaction::find($transaction_id);
        $items    = $transaction->transactionItems;
        $payment  = $transaction->payment;
        $product  = $items[0]->product;
        if(!$product || !$payment) return '';
        $customer = $transaction->customer;

        $filename = md5(md5($customer->id.".".$transaction->id.".".$transaction->created_at));
        $file_to_save = 'pdf/'.$filename.'.pdf';
        if(!file_exists($file_to_save))
        {
            $custom_fields = \App\Models\CustomField::where('class_target','App\Models\EventProduct')->get();
            $cf = [];
            $cf_product_id = isset($product->parent) ? $product->parent->parent->id : $product->id;
            foreach($custom_fields as $key => $value)
            {
                $cf[$value->field_key] = $value->get_value($cf_product_id)->field_value;
            }
    
            $participant_custom_fields = \App\Models\CustomField::where('class_target','App\Models\Event')->get();
            $participants = [];
            foreach($participant_custom_fields as $key => $value)
            {
                $cf_values = $value->customFieldValues()->where('pk_id',$items[0]->id)->get();
                foreach($cf_values as $cf_value)
                {
                    $participants[$key][] = $cf_value->field_value;
                }
            }

            $flip = [];
            if(empty($participants))
            {
                $flip[] = [$customer->full_name,' '];
            }
            else
            {
                $flip = array_map(null, ...$participants);
            }    
            $part = [];
            $qrcode = [];
            $no = 1;
            // hitung produk yang terjual (transaction paid)
            $terjual = TransactionItem::where('product_id',$product->id)->whereHas('transaction', function($q){
                $q->where('status','PAID');
            })->sum('amount');

            $start   = $terjual - $items[0]->amount;

            foreach($flip as $ps)
            {
                if(is_array($ps))
                {
                    $p = $start.', '.$ps[0].(isset($ps[1])?', '.$ps[1]:'');
                    $qr_content = $start.';'.$transaction->id.';'.$ps[0].(isset($ps[1])?';'.$ps[1]:'');
                }
                else
                {
                    $p = $start.', '.$ps;
                    $qr_content = $start.';'.$transaction->id.';'.$ps;
                }
                $part[] = $p;
    
                
                $start++;
                $barcode = file_get_contents("http://www.barcode-generator.org/phpqrcode/getCode.php?cht=qr&chl=".$qr_content."&chs=180x180&choe=UTF-8&chld=L|0");
                $qrcode[] = 'data:image/png;base64,' . base64_encode($barcode);
                $no++;
            }
    
            $path = public_path('/assets/images/e-tiket-bg.jpeg');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $bg   = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
            $content = view('pdf.ticket',compact('transaction','items','payment','product','customer','cf','part','bg','qrcode'))->render();
    
            $pdf = PDF::loadHTML($content)->setOptions(['defaultFont' => 'Courier'])->setPaper([0,0,440,580]);
            // ->stream('download.pdf');
            $filename = md5(md5($customer->id.".".$transaction->id.".".$transaction->created_at));
            $file_to_save = 'pdf/'.$filename.'.pdf';
            //save the pdf file on the server
            file_put_contents($file_to_save, $pdf->output());
        }

        return $file_to_save;
    }
}