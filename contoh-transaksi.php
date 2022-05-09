<?php
$merchantId = "******"; //wajib di isi
$secretKey = "********"; //wajib di isi , bisa di cek di https://member.apigames.id/pengaturan/secret-key
require 'apigames.class.php';
$apigames = new Apigames($merchantId,$secretKey);

//data transaksi, contoh Free fire 5 diamond kode FF5
$kodeProduk = "FF5";
$userId = "3193063752"; //isi player id ff
$serverId = ""; //kosongkan saja, karna ff tidak ada server id
$refId = "TRX0003"; //ref id sistem anda, ini harus unik, jika ada request ref id yg sama, maka yg di lakukan adalah pengecekan status transaksi, bukan melakukan transaksi
$trx = $apigames->transaksi($kodeProduk,$userId,$serverId,$refId); //bisa di echo $trx untuk melihat hasil transaksi json
$parsingTrx = json_decode($trx,true); //untuk mengetahui trx berhasil atau gagal
if (isset($parsingTrx['data']['status'])){
    $status = $parsingTrx['data']['status'];
    if ($status=="Sukses"){
        //Transaksi sukses di lakukan
        $sn = $parsingTrx['data']['sn'];
        echo "Transaksi $kodeProduk ke $userId$serverId Berhasil di lakukan, SN : $sn";
    }else if($status=="Gagal"){
        //Transaksi gagal di lakukan
        $pesanGagal = $parsingTrx['data']['sn'];
        echo "Transaksi $kodeProduk ke $userId$serverId Gagal di lakukan, Alasan Gagal : $pesanGagal";
    }else if($status=="Pending"){
        //Transaksi Pending sedang di proses operator
        echo "Transaksi $kodeProduk ke $userId$serverId Berhasul di teruskan, sedang di proses operator";
    }else{
        //Status transaksi belum ada response
        echo "Transaksi $kodeProduk ke $userId$serverId Belum ada status $status";
    }
}else{
    if (isset($parsingTrx['error_msg'])){
        //trx engine gagal, bisa jadi karna pengaturan secret key dan merchant id salah
        $errorMsg = $parsingTrx['error_msg'];
        echo $errorMsg;
    }else{
        //transaksi gagal di lakukan karna network error
        echo "Transaksi Gagal di lakukan karna network error atau $trx";
    }
}
