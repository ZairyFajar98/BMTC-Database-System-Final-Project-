<?php
    require 'config.php';
    echo 'tambah pembelian';

    $result = pg_query($db,'INSERT INTO Transaksi (id_motor) VALUES (NULL) RETURNING no_transaksi');
    if(!$result){
        $gagal = urlencode(pg_last_error($db));
        header("location:transaksitambah.php?gagal='$gagal'");
        echo $gagal;
    }else{
        $check = pg_affected_rows($result);
        if($check == 0){
            $gagal = urlencode('Data gagal dimasukkan');
            header("location:transaksitambah.php?gagal='$gagal'");
            echo $gagal;
        }else{
            $row = pg_fetch_assoc($result);
            $pk = $row["no_transaksi"];
            $no_transaksi = (int)$pk;
            header("location:transaksiread.php?status=ongoing&no_transaksi=$no_transaksi");
        }
    }
?>