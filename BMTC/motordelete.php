<?php
require ('config.php');

if(!isset($_POST["id_motor"])){
    //masuk tidak melalui form
    header('location:motor.php');
    exit;
}

$result = pg_query_params($db,"DELETE FROM Motor WHERE id_motor = $1",array($_POST["id_motor"]));
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:motor.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin dihapus tidak ditemukan');
        header("location:motor.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dihapus');
        header("location:motor.php?berhasil='$berhasil'");
    }
}

?>