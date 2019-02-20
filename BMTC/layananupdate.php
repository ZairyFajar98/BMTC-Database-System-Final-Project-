<?php
if(!isset($_POST["nama"])){
    //masuk tidak melalui form
    header('location:layanan.php');
}

require('config.php');

$parameter = array($_POST["harga"],$_POST["nama"]);
$result = pg_query_params($db,"UPDATE Layanan SET harga=$1 WHERE nama = $2",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:layanan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin diubah tidak ditemukan');
        header("location:layanan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil diupdate');
        header("location:layanan.php?berhasil='$berhasil'");
    }
}
?>