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
                <span class="mdl-layout-title">BMTC | Tambah Transaksi Pelayanan Profil</span>
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
                <form class="profil-form" action="transaksitambahpelayanannext.php" method="POST">

                    <div id="motor">
                        <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect">
                            DETAIL MOTOR
                        </button><br>
                        <div>
                            <div class="mdl-textfield mdl-js-textfield">
                                <p>Jenis Motor</p>
                                <input class="mdl-textfield__input" type="text" name="jenis_motor" id="jenis_motor"> 
                            </div><br>
                            <div class="mdl-textfield mdl-js-textfield">
                                <p>Plat Nomor</p>
                                <input class="mdl-textfield__input" type="text" name="plat_nomor" id="plat_nomor"> 
                            </div>
                        </div>
                    </div>

                    <div id="pelanggan">
                        <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect">
                            BUAT PROFIL PELANGGAN BARU
                        </button>
                        <div id="pelanggan-form">
                            <div class="mdl-textfield mdl-js-textfield">
                                <p>Nama Pelanggan</p>
                                <input disabled class="mdl-textfield__input" type="text" name="nama" id="nama"> 
                            </div><br>
                            <div class="mdl-textfield mdl-js-textfield">
                                <p>Nomor Handphone</p>
                                <input disabled class="mdl-textfield__input" type="text" name="no_hp" id="no_hp"> 
                            </div>
                        </div>
                        <br>ATAU<br>
                        <button type="button" class="mdl-button mdl-js-button mdl-js-ripple-effect">
                            PILIH PELANGGAN YANG SUDAH ADA
                        </button>
                        <div hidden id="pelanggan-ada">
                            <?php 
                                $result = pg_query($db,"select * from Pelanggan");
                                if($result){
                                    while($data = pg_fetch_assoc($result)){
                                        echo "<input selected type='radio' id='id_pelanggan' name='id_pelanggan' value=".$data['id_pelanggan'].">".$data['nama']."<br>";
                                    }
                                }else{
                                    echo "<script>console.log('query gagal')</script>";
                                }
                            ?>
                        </div><br> 
                    </div>
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                        BUAT
                    </button> 
                </form>
            </div>
        </section>

        <script>
            let form = document.querySelector('form');
            let motorBtn = document.querySelectorAll('#pelanggan button');
            let pelangganBaru = document.getElementById('pelanggan-form');
            let pelangganLama = document.getElementById('pelanggan-ada');
            motorBtn[0].addEventListener('click',function(){
                pelangganBaru.removeAttribute('hidden');
                pelangganLama.setAttribute('hidden','true');
                form.nama.disabled = false;
                form.no_hp.disabled = false;
            });
            motorBtn[1].addEventListener('click',function(){
                pelangganBaru.setAttribute('hidden','true');
                pelangganLama.removeAttribute('hidden');
                form.nama.disabled = true;
                form.no_hp.disabled = true;
            });
        </script>
        
    </body>
</html>