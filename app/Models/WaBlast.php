<?php

namespace App\Models;

class WaBlast
{
    
    static function send($to, $message)
    {
        $curl = curl_init();

        $postfields = http_build_query([
            'device_id' => getenv('WA_BLAST_DEVICE'),
            'number'    => $to,
            'message'   => $message
        ]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => getenv('WA_BLAST_URL')."/api/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // return ['status'=>'error','data'=>"cURL Error #:" . $err];
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}
