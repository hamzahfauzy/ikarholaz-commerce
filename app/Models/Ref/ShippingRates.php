<?php

namespace App\Models\Ref;

class ShippingRates
{
    static $dest, $weight, $courier, $origin;
    static function init($dest, $weight, $courier)
    {
        self::$dest = $dest;
        self::$weight = $weight;
        self::$courier = $courier;
		self::$origin = config('rajaongkir.origin');
        return new static;
    }

    function get()
    {
		if(in_array(self::$courier,['pickup']))
		{
			$results = json_decode(json_encode(config('shipping.'.self::$courier)));
		}
		else
		{
			$curl = curl_init();
	
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_SSL_VERIFYHOST=> 0,
			CURLOPT_SSL_VERIFYPEER=>0,
			CURLOPT_POSTFIELDS => "origin=".self::$origin."&destination=".self::$dest."&weight=".self::$weight."&courier=".self::$courier,
			CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded",
				"key: ".config('rajaongkir.api_key')
			),
			));
	
			$response = curl_exec($curl);
			$err = curl_error($curl);
	
			curl_close($curl);
	
			$results = json_decode($response);
			$results = $results->rajaongkir->results[0]->costs;
		}

		return $results;
    }
}
