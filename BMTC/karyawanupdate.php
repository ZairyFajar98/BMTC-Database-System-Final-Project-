<?php
if(!isset($_POST["id_karyawan"])){
    //masuk tidak melalui form
    header('location:karyawan.php');
}

require ('config.php');

$parameter = array($_POST["nama"],$_POST["no_hp"],$_POST["id_karyawan"]);
$result = pg_query_params($db,"UPDATE Karyawan SET nama=$1, no_hp=$2 WHERE id_karyawan = $3",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:karyawan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin diubah tidak ditemukan');
        header("location:karyawan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil diupdate');
        header("location:karyawan.php?berhasil='$berhasil'");
    }
}
?>