<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Berita Baru - Admin Desa Klampok</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            padding: 40px;
            max-width: 800px;
            margin: auto;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group .checkbox-label {
            font-weight: normal;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <a href="dashboard.php" style="margin-bottom: 20px; display: inline-block;">← Kembali ke Dashboard</a>
        <h2>Tambah Berita Baru</h2>
        <div class="form-container">
            <!-- Form akan dikirim ke proses.php. enctype diperlukan untuk upload file -->
            <form action="proses.php?aksi=tambah" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="judul">Judul Berita:</label>
                    <input type="text" id="judul" name="judul" required>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar Utama:</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*" required>
                    <p style="font-size: 0.8rem; color: #666;">Ukuran gambar disarankan 800x450 piksel.</p>
                </div>

                <div class="form-group">
                    <label for="ringkasan">Ringkasan (untuk slider dan daftar berita):</label>
                    <textarea id="ringkasan" name="ringkasan" rows="3" required maxlength="255"></textarea>
                </div>

                <div class="form-group">
                    <label for="isi_berita">Isi Berita Lengkap:</label>
                    <textarea id="isi_berita" name="isi_berita" rows="10" required></textarea>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="is_headline" name="is_headline" value="1">
                    <label for="is_headline" class="checkbox-label">Tampilkan sebagai Berita Utama (Headline)?</label>
                </div>

                <button type="submit" class="btn">Simpan Berita</button>
            </form>
        </div>
    </div>
</body>

</html>