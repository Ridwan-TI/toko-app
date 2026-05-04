<?php 

header("Content-Type: application/json");

// 1. Panggil koneksi di PALING ATAS
require_once __DIR__ . '/koneksi.php';

// 2. Ambil data JSON
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);

// 3. Validasi input
if (isset($data['nama_barang'], $data['harga'])) {

    $nama_barang = mysqli_real_escape_string($koneksi, $data['nama_barang']);
    $harga       = (int) $data['harga'];

    $query = "INSERT INTO barang (nama_barang, harga) VALUES ('$nama_barang', $harga)";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {
        $id_baru = mysqli_insert_id($koneksi);

        echo json_encode([
            "status"  => "success",
            "message" => "Barang berhasil ditambahkan",
            "data"    => [
                "ID"          => $id_baru,
                "nama_barang" => $nama_barang,
                "harga"       => $harga
            ]
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal menyimpan data: " . mysqli_error($koneksi)
        ]);
    }

} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Data tidak ditemukan atau format JSON salah"
    ]);
}
?>
