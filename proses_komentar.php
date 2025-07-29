<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi dasar
    if (empty(trim($_POST['komentar'])) || empty($_POST['berita_id'])) {
        die("Komentar atau ID Berita tidak boleh kosong.");
    }

    $berita_id = (int)$_POST['berita_id'];
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : NULL;
    $komentar = htmlspecialchars(trim($_POST['komentar']));

    // Query INSERT sederhana (tanpa kolom 'nama')
    $stmt = $conn->prepare("INSERT INTO komentar (berita_id, parent_id, komentar) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $berita_id, $parent_id, $komentar);

    if ($stmt->execute()) {
        // Redirect kembali ke halaman berita, ini akan me-reload halaman
        header("Location: detail_berita.php?id=$berita_id#kolom-komentar");
        exit();
    } else {
        echo "Gagal mengirim komentar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>