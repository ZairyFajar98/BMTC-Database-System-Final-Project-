<?php
require ('config.php');

if(!isset($_POST["id_pelanggan"])){
    //masuk tidak melalui form
    header('location:pelanggan.php');
}

$result = pg_query_params($db,"DELETE FROM Pelanggan WHERE id_pelanggan = $1",array($_POST["id_pelanggan"]));
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:pelanggan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin dihapus tidak ditemukan');
        header("location:pelanggan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dihapus');
        header("location:pelanggan.php?berhasil='$berhasil'");
    }
}

?>