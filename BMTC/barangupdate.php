<?php
if(!isset($_POST["id_barang"])){
    //masuk tidak melalui form
    header('location:barang.php');
}

require ('config.php');

$parameter = array($_POST["nama"],$_POST["merk"],$_POST["jumlah"],$_POST["harga"],$_POST["id_barang"]);
$result = pg_query_params($db,"UPDATE Barang SET nama=$1, merk=$2, jumlah=$3, harga=$4 WHERE id_barang = $5",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:barang.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin diubah tidak ditemukan');
        header("location:barang.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil diupdate');
        header("location:barang.php?berhasil='$berhasil'");
    }
}
?>