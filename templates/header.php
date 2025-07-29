<?php
// Cukup panggil file konfigurasi satu kali di bagian paling atas
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Klampok - Kota Malang</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon"> 
</head>
<body>
    <header>
    <div class="container">
        <div class="logo">
            <a href="index.php" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                <img src="https://placehold.co/50x50/1F3A66/ffffff?text=K" alt="Logo Desa Klampok" style="margin-right: 15px; border-radius: 50%;">
                <div>
                    <h1>Desa Klampok</h1>
                    <p>Kota Malang</p>
                </div>
            </a>
        </div>
        
        <!-- Navigasi Desktop -->
        <nav class="main-nav">
            <ul>
                <li><a href="index.php#beranda">Beranda</a></li>
                <li><a href="index.php#berita-utama">Berita Utama</a></li>
                <li><a href="index.php#profil-desa">Profil Desa</a></li>
                <li><a href="index.php#potensi-unggulan">Potensi</a></li>
                <li><a href="index.php#galeri">Galeri</a></li>
                <li><a href="index.php#berita">Pengumuman</a></li>
                <li><a href="index.php#saran-masukan">Saran</a></li>
                <li><a href="index.php#kontak" class="nav-button">Informasi</a></li>
            </ul>
        </nav>

        <!-- Tombol Hamburger untuk tampilan mobile -->
        <button class="hamburger-menu" aria-label="Toggle navigation menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Overlay untuk menu mobile -->
    <div class="mobile-nav-overlay"></div>
</header>