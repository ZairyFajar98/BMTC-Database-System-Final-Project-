<?php
if(!isset($_POST["nama"])){
    //masuk tidak melalui form
    header('location:layanan.php');
}

require('config.php');

$parameter = array($_POST["nama"],$_POST["harga"]);
$result = pg_query_params($db,"INSERT INTO Layanan VALUES ($1,$2)",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:layanan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data gagal dimasukkan');
        header("location:layanan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dimasukkan');
        header("location:layanan.php?berhasil='$berhasil'");
    }
}
?>