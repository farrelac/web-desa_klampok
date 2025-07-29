<?php
// Selalu mulai sesi di setiap halaman yang berurusan dengan sesi
session_start();

// 1. Hapus semua variabel sesi.
// Ini akan menghapus semua data yang tersimpan di dalam $_SESSION,
// termasuk $_SESSION['admin_logged_in'].
$_SESSION = array();

// 2. Hancurkan sesi.
// Ini akan menghapus ID sesi dari server.
session_destroy();

// 3. Arahkan (redirect) pengguna kembali ke halaman login.
// Halaman login kita ada di 'index.php' di dalam folder 'admin'.
header("location: index.php");

// 4. Pastikan tidak ada kode lain yang dieksekusi setelah redirect.
exit;
?>