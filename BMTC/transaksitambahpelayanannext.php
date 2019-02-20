<?php
    require 'config.php';
    
    if(!isset($_POST["id_motor"])){
        //menambah profil motor
        if(isset($_POST["nama"])){
            //tambah pelanggan baru
            $parameter = array($_POST["nama"],$_POST["no_hp"]);
            $result = pg_query_params($db,"INSERT INTO Pelanggan (nama,no_hp) VALUES ($1,$2) RETURNING id_pelanggan",$parameter);
            if(!$result){
                $gagal = urlencode(pg_last_error($db));
                header("location:tambahtransaksi.php?gagal='$gagal'");
                exit;
            }else{
                $row = pg_fetch_assoc($result);
                $pk = $row["id_pelanggan"];
                $id_pelanggan = (int)$pk;
            }
        }else{
            //pelanggan sudah ada
            $id_pelanggan = $_POST["id_pelanggan"];
        }
        $parameter = array($_POST["jenis_motor"],$_POST["plat_nomor"],$id_pelanggan);
        $result = pg_query_params($db,"INSERT INTO Motor (jenis_motor,plat_nomor,id_pelanggan) VALUES ($1,$2,$3) RETURNING id_motor",$parameter);
        if(!$result){
            $gagal = urlencode(pg_last_error($db));
            header("location:tambahtransaksi.php?gagal='$gagal'");
            exit;
        }else{
            $row = pg_fetch_assoc($result);
            $pk = $row["id_motor"];
            $id_motor = (int)$pk;
        }
    }else{
        $id_motor = $_POST["id_motor"];
    }

    $result = pg_query($db,"INSERT INTO Transaksi (id_motor) VALUES ('$id_motor') RETURNING no_transaksi");
    if(!$result){
        $gagal = urlencode(pg_last_error($db));
        header("location:transaksitambahpelayanan.php?gagal='$gagal'");
        echo $gagal;
    }else{
        $check = pg_affected_rows($result);
        if($check == 0){
            $gagal = urlencode('Data gagal dimasukkan');
            header("transaksitambahpelayanan.php?gagal='$gagal'");
            echo $gagal;
        }else{
            $row = pg_fetch_assoc($result);
            $pk = $row["no_transaksi"];
            $no_transaksi = (int)$pk;
            header("location:transaksiread.php?status=ongoing&no_transaksi=$no_transaksi");
        }
    }
?>