<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

// Pastikan ada ID yang dikirim
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

require_once '../config/database.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM berita WHERE id = $id");

// Jika berita tidak ditemukan, kembali ke dashboard
if ($result->num_rows == 0) {
    header('Location: dashboard.php');
    exit();
}

$berita = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Berita - Admin Desa Klampok</title>
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

        .current-image {
            max-width: 200px;
            display: block;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <a href="dashboard.php" style="margin-bottom: 20px; display: inline-block;">← Kembali ke Dashboard</a>
        <h2>Edit Berita</h2>
        <div class="form-container">
            <form action="proses.php?aksi=edit" method="POST" enctype="multipart/form-data">
                <!-- Tambahkan input tersembunyi untuk menyimpan ID -->
                <input type="hidden" name="id" value="<?php echo $berita['id']; ?>">

                <div class="form-group">
                    <label for="judul">Judul Berita:</label>
                    <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($berita['judul']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar Utama:</label>
                    <p>Gambar Saat Ini:</p>
                    <img src="../uploads/<?php echo htmlspecialchars($berita['gambar']); ?>" alt="Gambar saat ini" class="current-image">
                    <br>
                    <input type="file" id="gambar" name="gambar" accept="image/*">
                    <p style="font-size: 0.8rem; color: #666;">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                </div>

                <div class="form-group">
                    <label for="ringkasan">Ringkasan:</label>
                    <textarea id="ringkasan" name="ringkasan" rows="3" required maxlength="255"><?php echo htmlspecialchars($berita['ringkasan']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="isi_berita">Isi Berita Lengkap:</label>
                    <textarea id="isi_berita" name="isi_berita" rows="10" required><?php echo htmlspecialchars($berita['isi_berita']); ?></textarea>
                </div>

                <div class="form-group">
                    <input type="checkbox" id="is_headline" name="is_headline" value="1" <?php if ($berita['is_headline'] == 1) echo 'checked'; ?>>
                    <label for="is_headline" class="checkbox-label">Tampilkan sebagai Berita Utama (Headline)?</label>
                </div>

                <button type="submit" class="btn">Update Berita</button>
            </form>
        </div>
    </div>
</body>

</html>