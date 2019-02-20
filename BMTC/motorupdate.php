<?php
if(!isset($_POST["id_motor"])){
    //masuk tidak melalui form
    header('location:motor.php');
}

require ('config.php');

$parameter = array($_POST["jenis_motor"],$_POST["plat_nomor"],$_POST["id_pelanggan"],$_POST["id_motor"]);
$result = pg_query_params($db,"UPDATE Motor SET jenis_motor=$1, plat_nomor=$2, id_pelanggan=$3 WHERE id_motor = $4",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:motor.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin diubah tidak ditemukan');
        header("location:motor.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil diupdate');
        header("location:motor.php?berhasil='$berhasil'");
    }
}
?>