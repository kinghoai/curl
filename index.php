<?php
class InventoryUpdate{
    public $tokenHost = '';
    public $host = '';

    public function __construct(){
        $this->tokenHost = 'https://account.demandware.com/dw/oauth2/access_token?grant_type=client_credentials';
        $this->host = 'https://bfgx-003.sandbox.us01.dx.commercecloud.salesforce.com/s/-/dw/batch';
    }

    public function createAccessToken(){
        $host = $this->tokenHost;
        $process = curl_init($host);
        curl_setopt($process, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic YWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhOmFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYWFhYQ=='
        ));
        curl_setopt($process, CURLOPT_HEADER, 1);
        curl_setopt($process, CURLOPT_TIMEOUT, 10);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);

        $curlHeaderLength = curl_getinfo($process, CURLINFO_HEADER_SIZE); // Header size is required to extract the response body /
        $lastResponseXml = substr($return, $curlHeaderLength);
        $response = json_decode($lastResponseXml, true);
        curl_close($process);
        $accessToken = $response['access_token'];
        return $accessToken;
    }

    public function createProductInventoryRecords() {
        $host = $this->host;
        $token = $this->createAccessToken();
        foreach(glob('./files/*.txt') as $fileName){
            $fileContent = file_get_contents($fileName);
            $process = curl_init($host);
            curl_setopt($process, CURLOPT_HTTPHEADER, array(
                'Content-Type: multipart/mixed; boundary=BOUNDARY',
                'x-dw-resource-path: /s/-/dw/data/v20_4/inventory_lists/inventory_m/product_inventory_records/',
                'Authorization: Bearer '. $token
            ));
            curl_setopt($process, CURLOPT_HEADER, 1);
            curl_setopt($process, CURLOPT_TIMEOUT, 1000);
            curl_setopt($process, CURLOPT_POST, 1);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($process, CURLOPT_POSTFIELDS, $fileContent);
            $return = curl_exec($process);
            var_dump($return);
            echo '<br/><br/>==========================================================================<br/><br/>';
        }
    }

    public function buildData() {
        $arrResult = [];
        $fileContent = file_get_contents('./file.txt');
        $arrData = explode('--BOUNDARY', $fileContent);
        foreach($arrData as $key=>$value) {
            $str = '';
            if(($key + 1) % 50 == 0) {
                $str += $arrData[]
            } else {
                $str += $arrData[$key]
            }
        }
    }
}

$inventoryUpdate  = new InventoryUpdate();
$inventoryUpdate->buildData();