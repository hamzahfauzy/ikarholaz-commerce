<?php

namespace App\Models\Ref;

class Tripay
{
    protected $api_PKey = '';
    protected $api_Key = '';
    // Database
    protected $dbH;
    protected $field_status = 'status'; // nama field status dalam tabel transaksi
    protected $field_kodetrans = 'idtrx'; // nama field kode dalam tabel transaksi
    protected $tabel_trans = 'trx'; // nama tabel transaksi

    // URLs Channel
    public $URL_channelPs = 'https://tripay.co.id/api-sandbox/payment/channel';
    public $URL_channelPp = 'https://tripay.co.id/api/payment/channel';
    public $URL_channelMs = 'https://tripay.co.id/api-sandbox/merchant/payment-channel';
    public $URL_channelMp = 'https://tripay.co.id/api/merchant/payment-channel';
    // URLs Calculator
    public $URL_calcMs = 'https://tripay.co.id/api-sandbox/merchant/fee-calculator';
    public $URL_calcMp = 'https://tripay.co.id/api/merchant/fee-calculator';
    /*
	* URLs Transaction
	*/
    // Create
    public $URL_transMs = 'https://tripay.co.id/api-sandbox/transaction/create';
    public $URL_transMp = 'https://tripay.co.id/api/transaction/create';
    public $URL_transOpenMs = '';
    public $URL_transOpenMp = 'https://tripay.co.id/api/transaction/open-payment/create';
    // Detail Close Sistem
    public $URL_transDetailMs = 'https://tripay.co.id/api-sandbox/transaction/detail';
    public $URL_transDetailMp = 'https://tripay.co.id/api/transaction/detail';
    // Detail Open Sistem with uuid
    public $URL_transDetailOpenMs = '';
    public $URL_transDetailOpenMp = 'https://tripay.co.id/api/transaction/open-payment/';
    public $URL_transPembOpenMs = '';
    public $URL_transPembOpenMp = 'https://tripay.co.id/api/transaction/open-payment/';

    public function __construct($privateKey = null, $apiKey = null)
    {
        if ($privateKey === null and $apiKey === null) {
            exit('Mohon isikan data private dan api key anda');
        } else {
            $this->api_PKey = $privateKey;
            $this->api_Key = $apiKey;
            $tripay_env = config('reference.tripay_env');
            if($tripay_env == 'sandbox')
            {
                $this->URL_channelMp = $this->URL_channelMs;
                $this->URL_channelPp = $this->URL_channelPs;
                $this->URL_transMp = $this->URL_transMs;
            }
        }
    }

    private function checkKey()
    {
        if ($this->api_PKey === null or $this->api_PKey === '' and $this->api_Key === null or $this->api_Key === '') {
            exit('Mohon isikan data private dan api key anda');
        } else {
            return true;
        }
    }

    private function dQuery($merchantH = null, $statusH = null)
    {
        $this->checkKey();
        if ($merchantH === null or $merchantH === '' and $statusH === null or $statusH === '' and $this->field_status === null or $this->field_status === '' and $this->field_kodetrans === null or $this->field_kodetrans === '' and $this->tabel_trans === null or $this->tabel_trans === '') {
            return false;
        } else {
            if ($statusH === 'UNPAID') {
                return "UPDATE " . addslashes($this->tabel_trans) . " SET " . addslashes($this->field_status) . " = 'UNPAID' WHERE " . addslashes($this->field_kodetrans) . " = '" . addslashes($merchantH) . "'";
            } elseif ($statusH === 'PAID') {
                return "UPDATE " . addslashes($this->tabel_trans) . " SET " . addslashes($this->field_status) . " = 'PAID' WHERE " . addslashes($this->field_kodetrans) . " = '" . addslashes($merchantH) . "'";
            } elseif ($statusH === 'EXPIRED') {
                return "UPDATE " . addslashes($this->tabel_trans) . " SET " . addslashes($this->field_status) . " = 'EXPIRED' WHERE " . addslashes($this->field_kodetrans) . " = '" . addslashes($merchantH) . "'";
            } elseif ($statusH === 'FAILED') {
                return "UPDATE " . addslashes($this->tabel_trans) . " SET " . addslashes($this->field_status) . " = 'FAILED' WHERE " . addslashes($this->field_kodetrans) . " = '" . addslashes($merchantH) . "'";
            } elseif ($statusH === 'REFUND') {
                return "UPDATE " . addslashes($this->tabel_trans) . " SET " . addslashes($this->field_status) . " = 'REFUND' WHERE " . addslashes($this->field_kodetrans) . " = '" . addslashes($merchantH) . "'";
            }
        }
    }

    public function callBack()
    {
        $this->checkKey();
        $result = array();
        // ambil data JSON
        $json = file_get_contents("php://input");

        // ambil callback signature
        $callbackSignature = isset($_SERVER['HTTP_X_CALLBACK_SIGNATURE']) ? $_SERVER['HTTP_X_CALLBACK_SIGNATURE'] : '';

        // generate signature untuk dicocokkan dengan X-Callback-Signature
        $signature = hash_hmac('sha256', $json, $this->api_PKey);

        // validasi signature
        if ($callbackSignature != $signature) {
            exit('Signature tidak valid'); // signature tidak valid, hentikan proses
        } else {
            // Data
            $data = json_decode($json);
            $event = $_SERVER['HTTP_X_CALLBACK_EVENT'];

            return $data;
        }
    }

    public function curlAPI($url = null, $payloads = null, $method = null)
    {
        try {
            //code...
            $this->checkKey();
            $result = array();
            $curl = curl_init();

            if ($method === null and $url === null) {
                $result['error'] = true;
                $result['status'] = 404;
            } elseif ($method === 'get' or $method === 'GET') {
                curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
                if ($payloads != null or $payloads != '') {
                    curl_setopt($curl, CURLOPT_URL, $url . "?" . http_build_query($payloads));
                } elseif (isset($payloads['uuid']) and isset($payloads['uuid_type'])) {
                    curl_setopt($curl, CURLOPT_URL, $url . $payloads['uuid'] . $payloads['uuid_type']);
                } else {
                    curl_setopt($curl, CURLOPT_URL, $url);
                }
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->api_Key));
                curl_setopt($curl, CURLOPT_FAILONERROR, false);

                $response = curl_exec($curl);
                $err = curl_error($curl);
                $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                if (!empty($err)) {
                    $result['error'] = true;
                    $result['status'] = $http_status;
                    $result['message'] = $err;
                } else {
                    $result = $response;
                }
            } elseif ($method === 'post' or $method === 'POST') {
                curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $this->api_Key));
                curl_setopt($curl, CURLOPT_FAILONERROR, false);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payloads));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                if (!empty($err)) {
                    $result['error'] = true;
                    $result['status'] = $http_status;
                    $result['message'] = $err;
                } else {
                    $result = $response;
                }
            }

            return json_decode($result, 1);
        } catch (\Throwable $th) {
            //throw $th;
            return ['data' => []];
        }
    }
}
