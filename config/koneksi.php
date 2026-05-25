<?php
$conn = mysqli_connect("localhost", "root", "", "dbsppsiswa"); 

if(!$conn) {
    die("koneksi database gagal" . mysqli_connect_error()); 
}