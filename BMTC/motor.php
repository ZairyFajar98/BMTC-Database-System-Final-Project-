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
                <span class="mdl-layout-title">BMTC | Motor</span>
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
                <p class="table-title">Profil motor yang sudah terdaftar:</p>
                <div class="tambah-baris">
                    <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab">
                        <i class="material-icons">add</i>
                    </button>
                    <p>Tambah Profil Motor</p>
                </div>

                <div class="wrapper">

                    <div class="table">
                        <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                            <thead>
                                <tr>
                                    <th>Jenis Motor</th>
                                    <th>Plat Nomor</th>
                                    <th>ID Pemilik</th>
                                    <th>Nama Pemilik</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $result = pg_query($db,"select id_motor, jenis_motor, plat_nomor, id_pelanggan, nama from Pelanggan inner join Motor using (id_pelanggan)");
                                if($result){
                                    while($data = pg_fetch_assoc($result)){
                                        echo "<tr id='".$data['id_motor']."'>
                                        <td>".$data['jenis_motor']."</td>
                                        <td>".$data['plat_nomor']."</td>
                                        <td>".$data['id_pelanggan']."</td>
                                        <td>".$data['nama']."</td>
                                        </tr>";
                                    }
                                }else{
                                    echo "<script>console.log('query gagal')</script>";
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="info">
                        <form method="post">
                            <p id="success-msg">
                                <?php 
                                    if(isset($_GET["berhasil"])){
                                        echo $_GET["berhasil"];
                                    }
                                ?>
                            </p>
                            <p id="error-msg">
                                <?php 
                                    if(isset($_GET["gagal"])){
                                        echo $_GET["gagal"];
                                    }
                                ?>
                            </p> 
                            <div class="mdl-textfield mdl-js-textfield">
                                <p>ID Motor</p>
                                <input class="mdl-textfield__input" type="text" name="id_motor" id="id_motor" readonly>
                            </div>
                            <div class="mdl-textfield mdl-js-textfield">
                                <p>Jenis Motor</p>
                                <input class="mdl-textfield__input" type="text" name="jenis_motor" id="jenis_motor">
                            </div>
                            <div class="mdl-textfield mdl-js-textfield">
                                <p>Plat Nomor</p>
                                <input class="mdl-textfield__input" type="text" name="plat_nomor" id="plat_nomor">
                            </div>
                            <div class="mdl-textfield mdl-js-textfield">
                                <p>ID Pemilik</p>
                                <input class="mdl-textfield__input" type="text" name="id_pelanggan" id="id_pelanggan"> 
                            </div>
                            <div class="info-button">
                                <button disabled formaction="motorcreate.php" class="mdl-button mdl-js-button mdl-button--primary">TAMBAH</button>
                                <button disabled formaction="motorupdate.php" class="mdl-button mdl-js-button mdl-button--primary">UBAH</button>
                                <button disabled formaction="motordelete.php" class="mdl-button mdl-js-button mdl-button--accent">HAPUS</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </section>

        <!-- JavaScript -->
        <script>
            let tableBody = document.querySelector('tbody');
            let infoForm = document.querySelector('.info form');
            let infoBtn = document.querySelectorAll('.info-button button');
            let successMsg = document.getElementById('success-msg');
            let errorMsg = document.getElementById('error-msg');
            tableBody.addEventListener('click',event => {
                successMsg.innerHTML = "";
                errorMsg.innerHTML = "";
                infoBtn[0].setAttribute('disabled','true');
                infoBtn[1].removeAttribute('disabled');
                infoBtn[2].removeAttribute('disabled');
                infoForm.id_motor.value = event.path[1].id;
                infoForm.jenis_motor.value = event.path[1].childNodes[1].innerText;
                infoForm.plat_nomor.value = event.path[1].childNodes[3].innerText;
                infoForm.id_pelanggan.value = event.path[1].childNodes[5].innerText;
            });
            let tambahBtn = document.querySelector('.tambah-baris button')
            tambahBtn.addEventListener('click',event => {
                successMsg.innerHTML = "";
                errorMsg.innerHTML = "";
                infoForm.id_motor.value = ""
                infoForm.jenis_motor.value = ""
                infoForm.plat_nomor.value = ""
                infoForm.id_pelanggan.value = ""
                infoBtn[0].removeAttribute('disabled');
                infoBtn[1].setAttribute('disabled','true');
                infoBtn[2].setAttribute('disabled','true');
            });
        </script>
    </body>
</html>