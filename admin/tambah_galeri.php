<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
// Definisikan kategori album
$kategori_album = [
    'pemerintahan' => 'Kegiatan Pemerintahan Desa',
    'pembangunan' => 'Pembangunan & Infrastruktur',
    'kemasyarakatan' => 'Kegiatan Kemasyarakatan & Sosial',
    'wisata_kuliner' => 'Wisata & Kuliner'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Foto Galeri - Admin</title>
    <link rel="stylesheet" href="../css/admin_form.css">
</head>
<body>
    <div class="form-container">
        <a href="dashboard.php?tab=galeri" class="back-link">← Kembali ke Dashboard</a>
        <h2>Tambah Foto Baru ke Galeri</h2>
        <form action="proses_galeri.php?aksi=tambah" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama_kegiatan">Nama Kegiatan/Judul Foto:</label>
                <input type="text" id="nama_kegiatan" name="nama_kegiatan" required>
            </div>
            
            <!-- DROPDOWN KATEGORI BARU -->
            <div class="form-group">
                <label for="kategori">Pilih Album Kategori:</label>
                <select id="kategori" name="kategori" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <?php foreach ($kategori_album as $key => $value): ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="gambar">File Gambar:</label>
                <input type="file" id="gambar" name="gambar" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="tanggal_kegiatan">Tanggal Kegiatan:</label>
                <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" required>
            </div>
            <button type="submit" class="btn">Simpan Foto</button>
        </form>
    </div>
</body>
</html>