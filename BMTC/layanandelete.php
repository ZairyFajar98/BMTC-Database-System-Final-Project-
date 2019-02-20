<?php
if(!isset($_POST["nama"])){
    //masuk tidak melalui form
    header('location:layanan.php');
}

require ('config.php');

$result = pg_query_params($db,"DELETE FROM Layanan WHERE nama = $1",array($_POST["nama"]));
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:layanan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin dihapus tidak ditemukan');
        header("location:layanan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dihapus');
        header("location:layanan.php?berhasil='$berhasil'");
    }
}
?>