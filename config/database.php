<?php
// Pengaturan Koneksi Database
$db_host = 'localhost'; // atau 127.0.0.1
$db_user = 'root';
$db_pass = ''; // Biasanya kosong di Laragon
$db_name = 'desaklampok_db';

// Buat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set timezone agar sesuai dengan waktu di Indonesia
date_default_timezone_set('Asia/Jakarta');
?>