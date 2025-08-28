<?php
require_once 'config/database.php';

// Pastikan request adalah metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak diizinkan.");
}

// Ambil dan bersihkan data dari form
$berita_id = (int)($_POST['berita_id'] ?? 0);
$parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : NULL;
$komentar = trim($_POST['komentar'] ?? '');

// Validasi data dasar
if (empty($komentar) || $berita_id === 0) {
    die("Data tidak lengkap. Komentar dan ID berita wajib diisi.");
}

// =========================================================
// LOGIKA UTAMA: Cek apakah ini komentar utama atau balasan
// =========================================================

if ($parent_id === NULL) {
    // --- INI ADALAH KOMENTAR UTAMA ---
    // Ambil nama dari form. Jika kosong, set sebagai 'Anonim'.
    $nama = trim($_POST['nama'] ?? 'Anonim');
    if (empty($nama)) {
        $nama = 'Anonim';
    }

    // Siapkan query untuk INSERT dengan kolom 'nama'
    $stmt = $conn->prepare("INSERT INTO komentar (berita_id, parent_id, nama, komentar) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $berita_id, $parent_id, $nama, $komentar);

} else {
    // --- INI ADALAH BALASAN KOMENTAR ---
    // Nama tidak diambil dari form. Database akan otomatis mengisi 'Anonim' (jika sudah di-set DEFAULT).
    // Siapkan query untuk INSERT tanpa kolom 'nama'.
    $stmt = $conn->prepare("INSERT INTO komentar (berita_id, parent_id, komentar) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $berita_id, $parent_id, $komentar);
}

// Eksekusi query
if ($stmt->execute()) {
    // Ambil ID dari komentar yang baru saja dimasukkan
    $last_id = $conn->insert_id;
    // Redirect kembali ke halaman berita dengan anchor ke komentar baru
    header("Location: detail_berita.php?id=$berita_id#comment-$last_id");
    exit();
} else {
    // Jika gagal, tampilkan error
    echo "Gagal mengirim komentar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>