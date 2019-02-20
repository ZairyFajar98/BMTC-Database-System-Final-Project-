<?php 
require ('config.php');

if(!isset($_POST["id_barang"])){
    header('location:barang.php');
    exit;
}
$parameter = array($_POST["nama"],$_POST["merk"],$_POST["jumlah"],$_POST["harga"]);
$result = pg_query_params($db,"INSERT INTO Barang (nama,merk,jumlah,harga) values ($1,$2,$3,$4)",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:barang.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data gagal dimasukkan');
        header("location:barang.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dimasukkan');
        header("location:barang.php?berhasil='$berhasil'");
    }
}
?>