<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || !isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

require_once '../config/database.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM galeri WHERE id = $id");
if ($result->num_rows == 0) {
    header('Location: dashboard.php?tab=galeri');
    exit();
}
$foto = $result->fetch_assoc();

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
    <title>Edit Foto Galeri - Admin</title>
    <link rel="stylesheet" href="../css/admin_form.css">
    <style>.current-image { max-width: 200px; display: block; margin-top: 10px; border-radius: 5px; }</style>
</head>
<body>
    <div class="form-container">
        <a href="dashboard.php?tab=galeri" class="back-link">← Kembali ke Dashboard</a>
        <h2>Edit Foto Galeri</h2>
        <form action="proses_galeri.php?aksi=edit" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $foto['id']; ?>">
            <input type="hidden" name="gambar_lama" value="<?php echo $foto['gambar']; ?>">

            <div class="form-group">
                <label for="nama_kegiatan">Nama Kegiatan/Judul Foto:</label>
                <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="<?php echo htmlspecialchars($foto['nama_kegiatan']); ?>" required>
            </div>

            <!-- DROPDOWN KATEGORI BARU -->
            <div class="form-group">
                <label for="kategori">Pilih Album Kategori:</label>
                <select id="kategori" name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori_album as $key => $value): ?>
                        <option value="<?php echo $key; ?>" <?php if ($foto['kategori'] == $key) echo 'selected'; ?>>
                            <?php echo $value; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="gambar">File Gambar:</label>
                <p>Gambar Saat Ini:</p>
                <img src="../uploads/<?php echo htmlspecialchars($foto['gambar']); ?>" alt="Gambar saat ini" class="current-image">
                <br>
                <input type="file" id="gambar" name="gambar" accept="image/*">
                <p>Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat:</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($foto['deskripsi']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="tanggal_kegiatan">Tanggal Kegiatan:</label>
                <input type="date" id="tanggal_kegiatan" name="tanggal_kegiatan" value="<?php echo htmlspecialchars($foto['tanggal_kegiatan']); ?>" required>
            </div>
            <button type="submit" class="btn">Update Foto</button>
        </form>
    </div>
</body>
</html>