<?php 
    require 'config.php';
    if(!isset($_GET["no_transaksi"])){
        header('location:index.php');
        exit;
    }
    $no_transaksi = $_GET["no_transaksi"];
    $result = pg_query($db,"select * from Transaksi where no_transaksi = '$no_transaksi' and status_transaksi = 'ongoing'");
    if(!$data = pg_fetch_assoc($result)){
        header('location:index.php');
        exit;
    }
    $result = pg_query_params($db,"UPDATE Transaksi SET status_transaksi='done' WHERE no_transaksi = $1",array($no_transaksi));
    if(!$result){
        $gagal = urlencode(pg_last_error($db));
        header("location:transaksiread.php?status=ongoing&no_transaksi='$no_transaksi'&gagal='$gagal'");
    }else{
        $check = pg_affected_rows($result);
        if($check == 0){
            $gagal = urlencode('Data yang ingin diupdate tidak ditemukan');
            header("location:transaksiread.php?status=ongoing&no_transaksi='$no_transaksi'&gagal='$gagal'");
        }else{
            header("location:index.php");
        }
    }
?>