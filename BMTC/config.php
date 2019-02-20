<?php
	$host = "localhost";
	$port = "5432";
	$dbname = "bmtc";
	$user = "postgres";
	$password = "";
	$db = pg_connect( "host='$host' port='$port' dbname='$dbname' user='$user' password='$password'" );
	if(!$db){
        echo "<script>console.log('gagal connect ke database')</script>";
	}
	else{
        echo "<script>console.log('berhasil connect ke database')</script>";
	}
?>