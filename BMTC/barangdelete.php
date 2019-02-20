<?php
if(!isset($_POST["id_barang"])){
    //masuk tidak melalui form
    header('location:barang.php');
}
require ('config.php');

$result = pg_query_params($db,"DELETE FROM Barang WHERE id_barang = $1",array($_POST["id_barang"]));
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:barang.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin dihapus tidak ditemukan');
        header("location:barang.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dihapus');
        header("location:barang.php?berhasil='$berhasil'");
    }
}
?>

