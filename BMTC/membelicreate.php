<?php
if(!isset($_POST["no_transaksi"])){
    //masuk tidak melalui form
    header('location:index.php');
}

require ('config.php');

$no_transaksi = $_POST["no_transaksi"];
$parameter = array($_POST["no_transaksi"],$_POST["id_barang"],$_POST["jumlah"]);
$result = pg_query_params($db,"INSERT INTO Membeli VALUES ($1,$2,$3)",$parameter);
if(!$result){
    $gagal = urlencode(pg_last_error($db));
    header("location:transaksitambahmembeli.php?no_transaksi=$no_transaksi&gagal='$gagal'");
}else{
    $berhasil = urlencode('data berhasil dimasukkan');
    header("location:transaksiread.php?status=ongoing&no_transaksi=$no_transaksi");
}
?>