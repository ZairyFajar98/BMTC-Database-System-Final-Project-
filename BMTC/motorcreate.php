<?php
if(!isset($_POST["id_motor"])){
    //masuk tidak melalui form
    header('location:motor.php');
}

require ('config.php');

$parameter = array($_POST["jenis_motor"],$_POST["plat_nomor"],$_POST["id_pelanggan"]);
$result = pg_query_params($db,"INSERT INTO Motor (jenis_motor,plat_nomor,id_pelanggan) VALUES ($1,$2,$3)",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:motor.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data gagal dimasukkan');
        header("location:motor.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dimasukkan');
        header("location:motor.php?berhasil='$berhasil'");
    }
}
?>