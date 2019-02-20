<?php 
    require ('config.php');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>BMTC</title>
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
                <span class="mdl-layout-title">BMTC | Beranda</span>
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
                <p class="table-title">Daftar Transaksi:</p>
                <a href="./transaksitambah.php">
                    <div class="tambah-baris">
                        <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
                            <i class="material-icons">add</i>
                        </button>
                        <p>Tambah Transaksi</p>
                    </div>
                </a>
                <nav id="transaction">
                    <div class="active"><p>Sedang Berlangsung</p></div>
                    <div><p>Selesai</p></div>
                </nav>

                <div class="transaction-wrapper">
                    <?php 
                        $result = pg_query($db,"select * from Transaksi where status_transaksi='ongoing'");
                        if($result){
                            while($data = pg_fetch_assoc($result)){
                                $class = "";
                                if($data["id_motor"]){
                                    $class = "layan"; 
                                }
                                echo "<a href='./transaksiread.php?status=ongoing&no_transaksi=".$data["no_transaksi"]."'>
                                <div class='".$class."'>
                                <p>nomor: ".$data["no_transaksi"]."</p>
                                <p>id motor: ".$data["id_motor"]."</p>
                                <p>tanggal masuk: ".$data["tanggal_masuk"]."</p>
                                <p>harga terhitung: ".$data["total_harga"]."</p>
                                </div></a>";
                            }
                        }else{
                            echo "<script>console.log('query gagal')</script>";
                        }
                    ?>
                </div>
                <div hidden class="transaction-wrapper">
                    <?php 
                        $result = pg_query($db,"select * from Transaksi where status_transaksi='done'");
                        if($result){
                            while($data = pg_fetch_assoc($result)){
                                $class = "";
                                if($data["id_motor"]){
                                    $class = "layan"; 
                                }
                                echo "<a href='./transaksiread.php?status=done&no_transaksi=".$data["no_transaksi"]."'>
                                <div class='".$class."'>
                                <p>nomor: ".$data["no_transaksi"]."</p>
                                <p>id motor: ".$data["id_motor"]."</p>
                                <p>tanggal keluar: ".$data["tanggal_keluar"]."</p>
                                <p>total_harga: ".$data["total_harga"]."</p>
                                </div></a>";
                            }
                        }else{
                            echo "<script>console.log('query gagal')</script>";
                        }
                    ?>
                </div>


            </div>
        </section>
        <script>
            let nav = document.querySelectorAll('#transaction div');
            let choosen = document.getElementsByClassName('transaction-wrapper');
            nav[0].addEventListener('click',function(){
                nav[0].classList.add('active');
                nav[1].classList.remove('active');
                choosen[1].setAttribute('hidden','true');
                choosen[0].removeAttribute('hidden');
            });
            nav[1].addEventListener('click',function(){
                nav[1].classList.add('active');
                nav[0].classList.remove('active');
                choosen[0].setAttribute('hidden','true');
                choosen[1].removeAttribute('hidden');
            });
        </script>
    </body>
</html>