<?php
if(!isset($_POST["id_pelanggan"])){
    //masuk tidak melalui form
    header('location:pelanggan.php');
}

require ('config.php');

$parameter = array($_POST["nama"],$_POST["no_hp"],$_POST["id_pelanggan"]);
$result = pg_query_params($db,"UPDATE Pelanggan SET nama=$1, no_hp=$2 WHERE id_pelanggan = $3",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:pelanggan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin diubah tidak ditemukan');
        header("location:pelanggan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil diupdate');
        header("location:pelanggan.php?berhasil='$berhasil'");
    }
}
?>