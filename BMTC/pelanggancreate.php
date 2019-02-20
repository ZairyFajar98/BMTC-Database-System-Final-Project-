<?php
if(!isset($_POST["id_pelanggan"])){
    //masuk tidak melalui form
    header('location:pelanggan.php');
}

require ('config.php');

$parameter = array($_POST["nama"],$_POST["no_hp"]);
$result = pg_query_params($db,"INSERT INTO Pelanggan (nama,no_hp) VALUES ($1,$2)",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:pelanggan.php?gagal='$gagal'");
}else{
    $check = pg_affected_rows($result);
    if($check == 0){
        $gagal = urlencode('Data gagal dimasukkan');
        header("location:pelanggan.php?gagal='$gagal'");
    }else{
        $berhasil = urlencode('data berhasil dimasukkan');
        header("location:pelanggan.php?berhasil='$berhasil'");
    }
}
?>