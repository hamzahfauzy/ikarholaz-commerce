<?php
namespace App\Libraries;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfAction
{
    function ticketUrl($transaction_id)
    {
        $transaction = \App\Models\Transaction::find($transaction_id);
        $items    = $transaction->transactionItems;
        $payment  = $transaction->payment;
        $product  = $items[0]->product;
        $customer = $transaction->customer;

        $filename = md5(md5($customer->id.".".$transaction->id.".".$transaction->created_at));
        $file_to_save = 'pdf/'.$filename.'.pdf';
        if(!file_exists($file_to_save))
        {
            $custom_fields = \App\Models\CustomField::where('class_target','App\Models\EventProduct')->get();
            $cf = [];
            $cf_product_id = $product->parent ? $product->parent->parent->id : $product->id;
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
    
            $flip = array_map(null, ...$participants);
            $part = "";
            $qr_content = "";
            $no = 1;
            foreach($flip as $ps)
            {
                $part .= $no.'. '.$ps[0].', '.$ps[1];
                $part .= "\n";
    
                $qr_content .= $transaction->id.';'.$ps[0].';'.$ps[1].PHP_EOL;
                $no++;
            }
    
            $path = public_path('/assets/images/e-tiket-bg.jpeg');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $bg   = 'data:image/' . $type . ';base64,' . base64_encode($data);
    
            $barcode = file_get_contents("http://www.barcode-generator.org/phpqrcode/getCode.php?cht=qr&chl=".$qr_content."&chs=180x180&choe=UTF-8&chld=L|0");
            $qrcode = 'data:image/png;base64,' . base64_encode($barcode);
    
    
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