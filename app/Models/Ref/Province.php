<?php

namespace App\Models\Ref;

class Province
{
    static function get()
    {
        if(!file_exists('provinces.json'))
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYHOST=> 0,
                CURLOPT_SSL_VERIFYPEER=>0,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "key: ".config('rajaongkir.api_key')
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            
            if($err)
                print_r($err);

            file_put_contents('provinces.json',$response);
        }
        else
        {
            $response = file_get_contents('provinces.json');
        }

		$results = json_decode($response);
        // print_r(env('ra'));
		return $results->rajaongkir->results;
    }

    static function find($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province?id=$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST=> 0,
            CURLOPT_SSL_VERIFYPEER=>0,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: ".config('rajaongkir.api_key')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        if($err)
            print_r($err);

        $results = json_decode($response);
        // print_r(env('ra'));
        return $results->rajaongkir->results;
    }
}
