<?php 
    require 'config.php';
    if(!isset($_GET["no_transaksi"])){
        header('location:index.php');
        exit;
    }
    $no_transaksi = $_GET["no_transaksi"];
    $result = pg_query($db,"select * from Transaksi where no_transaksi = '$no_transaksi'");
    if(!$data = pg_fetch_assoc($result)){
        echo 'gagal';
        // header('location:index.php');
        // exit;
    }else{
        $id_motor = $data["id_motor"];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>BMTC | Transaksi</title>
        <!-- Material Design Lite -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.deep_purple-pink.min.css"/>
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <!-- own stylesheet -->
        <link rel="stylesheet" href="./stylesheets/style.css">
    </head>
    <body>
        <header class="mdl-layout__header mdl-layout__header--transparent">
            <div class="mdl-layout__header-row">
                <!-- Title -->
                <span class="mdl-layout-title">BMTC | Transaksi</span>
                <!-- Add spacer, to align navigation to the right -->
                <div class="mdl-layout-spacer"></div>
                <!-- Navigation -->
                <nav class="mdl-navigation">
                    <a class="mdl-navigation__link" href="index.php">Beranda</a>
                    <a class="mdl-navigation__link" href="barang.php">Barang</a>
                    <a class="mdl-navigation__link" href="layanan.php">Layanan</a>
                    <a class="mdl-navigation__link" href="karyawan.php">Karyawan</a>
                    <a class="mdl-navigation__link" href="motor.php">Motor</a>
                    <a class="mdl-navigation__link" href="pelanggan.php">Pelanggan</a>
                </nav>
            </div>
        </header>

        <section>
            <div class="container">
                <p class="table-title">Detail Transaksi:</p>
                <div class="tambah-baris">
                    <a href='
                    <?php 
                        if($_GET["status"] == "done"){
                            echo "transaksihapus.php?no_transaksi=".$no_transaksi;
                        }else{
                            echo "transaksiselesai.php?no_transaksi=".$no_transaksi;
                        }
                    ?>
                    '>
                        <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
                            <i class="material-icons">
                                <?php 
                                    if($_GET["status"] == "done"){
                                        echo "close";
                                    }else{
                                        echo "check";
                                    }
                                ?>
                            </i>
                        </button>
                        <p>
                        <?php 
                            if($_GET["status"] == "done"){
                                echo "Hapus Transaksi";
                            }else{
                                echo "Selesaikan Transaksi";
                            }
                        ?>
                        </p>
                    </a>
                    
                </div>
                <div class="wrapper">
                    <div class="detail">

                        <h5>INFO</h5>
                        <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                            <tbody>
                                <?php 
                                    $result = pg_query($db,"select * from 
                                    Transaksi left join Motor using (id_motor)
                                    left join Pelanggan using (id_pelanggan)
                                    where no_transaksi = '$no_transaksi'");
                                    if($result){
                                        $data = pg_fetch_assoc($result);
                                        echo "<tr><td>Nomor Transaksi</td><td>".$data["no_transaksi"]."</td></tr>";
                                        echo "<tr><td>Motor </td><td>".$data["jenis_motor"]."</td></tr>";
                                        echo "<tr><td>Plat Nomor</td><td>".$data["plat_nomor"]."</td></tr>";
                                        echo "<tr><td>Pemilik Motor</td><td>".$data["nama"]."</td></tr>";
                                        echo "<tr><td>Tanggal Masuk</td><td>".$data["tanggal_masuk"]."</td></tr>";
                                        echo "<tr><td>Tanggal Keluar</td><td>".$data["tanggal_keluar"]."</td></tr>";
                                        echo "<tr><td>Total Harga</td><td>".$data["total_harga"]."</td></tr>";
                                    }else{
                                        echo "<script>console.log('query gagal')</script>";
                                    }
                                ?>
                            </tbody>
                        </table>

                        <h5>DAFTAR PEMBELIAN</h5>
                        <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Merk</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $result = pg_query($db,"select id_barang, Membeli.jumlah as jumlah, nama, merk
                                    from Transaksi inner join Membeli using (no_transaksi)
                                    inner join Barang using (id_barang)
                                    where no_transaksi = '$no_transaksi'");
                                    if($result){
                                        while($data = pg_fetch_assoc($result)){
                                            echo "<tr>
                                            <td>".$data['nama']."</td>
                                            <td>".$data['merk']."</td>
                                            <td>".$data['jumlah']."</td>
                                            </tr>";
                                        }
                                    }else{
                                        echo "<script>console.log('query gagal')</script>";
                                    }
                                ?>
                            </tbody>
                        </table>

                        <h5>DAFTAR PELAYANAN</h5>
                        <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                            <thead>
                                <tr>
                                    <th>Nama Layanan</th>
                                    <th>Karyawan</th>
                                    <th>Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $result = pg_query($db,"select nama_layanan, Karyawan.nama as nama_karyawan, harga
                                    from Transaksi inner join Melayani using (no_transaksi) 
                                    inner join Karyawan using (id_karyawan) 
                                    inner join Layanan on Melayani.nama_layanan = Layanan.nama
                                    where no_transaksi = '$no_transaksi'");
                                    if($result){
                                        while($data = pg_fetch_assoc($result)){
                                            echo "<tr>
                                            <td>".$data['nama_layanan']."</td>
                                            <td>".$data['nama_karyawan']."</td>
                                            <td>".$data['harga']."</td>
                                            </tr>";
                                        }
                                    }else{
                                        echo "<script>console.log('query gagal')</script>";
                                    }
                                ?>
                            </tbody>
                        </table>

                    </div>
                    <div <?php 
                        if($_GET["status"] == "done"){
                            echo "hidden";
                        }
                    ?> class="detail-action">
                        <a href="./transaksitambahmelayani.php?no_transaksi=<?= $no_transaksi ?>"
                        <?php 
                            if(!$id_motor){
                                echo 'hidden';
                            }
                        ?>
                        href="">
                            <div>
                                <p>tambah layanan</p>
                            </div>
                        </a>
                        <a href="./transaksitambahmembeli.php?no_transaksi=<?= $no_transaksi ?>">
                            <div>
                                <p>tambah pembelian</p>
                            </div>
                        </a>
                    </div>
                </div>

                

            </div>
        </section>
        
    </body>
</html>