<?php
if(!isset($_POST["id_karyawan"])){
    //masuk tidak melalui form
    header('location:karyawan.php');
}

require ('config.php');

$result = pg_query_params($db,"DELETE FROM Karyawan WHERE id_karyawan = $1",array($_POST["id_karyawan"]));
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:karyawan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data yang ingin dihapus tidak ditemukan');
        header("location:karyawan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dihapus');
        header("location:karyawan.php?berhasil='$berhasil'");
    }
}
?>
