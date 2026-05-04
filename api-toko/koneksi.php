<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "sql300.infinityfree.com";
$user = "if0_41816596";
$pass = "Sekadim1";
$db   = "if0_41816596_store";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die(json_encode([
        "status" => "error",
        "pesan" => "Koneksi Database Gagal: " . mysqli_connect_error()
    ]));
}
?>
