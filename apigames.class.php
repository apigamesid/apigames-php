<?php
class Apigames
{
    public $merchantId = '';
    public $secretKey = '';
    public $generalSignature;
    public $apiUrl = "https://v1.apigames.id";

    public function __construct($merchantId, $secretKey)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->generalSignature = md5("$merchantId$secretKey");
    }

    private function generateSignatureTrx($refId,$kodeProduk,$userIdTujuan,$serverIdTujuan){
        //signature formula md5(secret_key + merchant_id + ref_id + product_code + tujuan)
        $signatureTrx = md5($this->secretKey.$this->merchantId.$refId.$kodeProduk.$userIdTujuan.$serverIdTujuan);
        return $signatureTrx;
    }

    public function transaksi($kodeProduk, $userIdTujuan, $serverIdTujuan, $refId){
        $signatureTrx = $this->generateSignatureTrx($refId,$kodeProduk,$userIdTujuan,$serverIdTujuan);
        $dataPost = [
            'ref_id'=>$refId,
            'merchant_id'=>$this->merchantId,
            'produk'=>$kodeProduk,
            'tujuan'=>$userIdTujuan.$serverIdTujuan,
            'signature'=>$signatureTrx,
        ];
        $dataPostJson = json_encode($dataPost);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl."/transaksi",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 300,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $dataPostJson,
        ));
        $response = curl_exec($curl);
        return $response;
    }
}
?>
