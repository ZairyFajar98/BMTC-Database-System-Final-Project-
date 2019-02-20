<?php
if(!isset($_POST["id_karyawan"])){
    //masuk tidak melalui form
    header('location:karyawan.php');
}

require ('config.php');

$parameter = array($_POST["nama"],$_POST["no_hp"]);
$result = pg_query_params($db,"INSERT INTO Karyawan (nama,no_hp) VALUES ($1,$2)",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:karyawan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data gagal dimasukkan');
        header("location:karyawan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dimasukkan');
        header("location:karyawan.php?berhasil='$berhasil'");
    }
}
?>