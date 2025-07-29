<?php
// Set Header untuk Respon JSON
header('Content-Type: application/json');

// --- Pengaturan Koneksi Database ---
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'desaklampok_db';

// Buat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi database gagal.']);
    exit();
}

// --- Menerima Data dari Form ---
// Hanya ambil 'name' dan 'message'
$nama = isset($_POST['name']) ? trim($_POST['name']) : '';
$pesan = isset($_POST['message']) ? trim($_POST['message']) : '';

// Validasi sederhana: pastikan pesan tidak kosong
if (empty($pesan)) {
    echo json_encode(['status' => 'error', 'message' => 'Kolom saran/masukan tidak boleh kosong.']);
    exit();
}

// Jika nama kosong, beri nilai default "Anonim"
if (empty($nama)) {
    $nama = 'Anonim';
}

// --- Menyimpan Data ke Database ---
// Pastikan tabel `feedback` Anda tidak memiliki kolom `email` atau kolom `email` di-set agar bisa NULL
// Jika tabel Anda punya kolom 'email' dan wajib diisi (NOT NULL), Anda harus mengubah struktur tabelnya dulu.
// Asumsi tabel 'feedback' hanya perlu 'nama' dan 'pesan'.
try {
    $stmt = $conn->prepare("INSERT INTO feedback (nama, pesan) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama, $pesan);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Terima kasih! Masukan Anda telah kami terima.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan masukan. Silakan coba lagi.']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan pada server.']);
}

$conn->close();
?>
