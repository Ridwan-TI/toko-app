<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");  // ← ini yang kurang
header("Content-Type: application/json");

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/koneksi.php';
// ... sisa kode

// =======================
// METHOD GET (AMBIL DATA)
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $query = "SELECT * FROM barang ORDER BY ID DESC";
    $result = mysqli_query($koneksi, $query);

    $data = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data"   => $data
    ]);
}

// =======================
// METHOD POST (TAMBAH)
// =======================
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);

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
            "message" => "Data tidak lengkap"
        ]);
    }
}

// =======================
// METHOD LAIN (OPTIONAL)
// =======================
else {
    echo json_encode([
        "status" => "error",
        "message" => "Method tidak diizinkan"
    ]);
}

?>
