<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

require_once '../config/database.php';

// Ambil data Berita
$result_berita = $conn->query("SELECT * FROM berita ORDER BY tanggal_publikasi DESC");

// Ambil data Galeri
$result_galeri = $conn->query("SELECT * FROM galeri ORDER BY tanggal_kegiatan DESC");

// Tentukan tab yang aktif (default: berita)
$tab_aktif = isset($_GET['tab']) ? $_GET['tab'] : 'berita';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Desa Klampok</title>
    <link rel="stylesheet" href="../css/admin_style.css"> <!-- Ganti ke CSS Admin khusus -->
</head>

<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Dashboard Admin</h1>
            <a href="logout.php" class="btn btn-logout">Logout</a>
        </div>

        <div class="admin-tabs">
            <a href="?tab=berita" class="tab-link <?php echo ($tab_aktif == 'berita') ? 'active' : ''; ?>">Manajemen Berita</a>
            <a href="?tab=galeri" class="tab-link <?php echo ($tab_aktif == 'galeri') ? 'active' : ''; ?>">Manajemen Galeri</a>
        </div>

        <?php if (isset($_GET['status'])): ?>
            <p class="status-message">
                <?php
                if ($_GET['status'] == 'sukses_tambah_berita') echo "Berita berhasil ditambahkan!";
                if ($_GET['status'] == 'sukses_tambah_galeri') echo "Foto galeri berhasil ditambahkan!";

                // TAMBAHKAN INI
                if ($_GET['status'] == 'sukses_edit_galeri') echo "Foto galeri berhasil diperbarui!";
                if ($_GET['status'] == 'sukses_hapus_galeri') echo "Foto galeri berhasil dihapus!";
                ?>
            </p>
        <?php endif; ?>

        <!-- Konten Manajemen Berita -->
        <div id="berita" class="tab-content <?php echo ($tab_aktif == 'berita') ? 'active' : ''; ?>">
            <div class="content-header">
                <h2>Daftar Berita</h2>
                <a href="tambah_berita.php" class="btn">Tambah Berita Baru</a>
            </div>
            <table>
                <!-- ... (tabel berita Anda yang sudah ada, tidak perlu diubah) ... -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Headline</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_berita->num_rows > 0): $no = 1; ?>
                        <?php while ($row = $result_berita->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><img src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="thumbnail" width="100"></td>
                                <td><?php echo htmlspecialchars($row['judul']); ?></td>
                                <td><?php echo ($row['is_headline'] == 1) ? 'Ya' : 'Tidak'; ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($row['tanggal_publikasi'])); ?></td>
                                <td class="action-links">
                                    <a href="edit_berita.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                    <a href="proses_berita.php?aksi=hapus&id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Yakin hapus berita ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Belum ada berita.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Konten Manajemen Galeri (BARU) -->
        <div id="galeri" class="tab-content <?php echo ($tab_aktif == 'galeri') ? 'active' : ''; ?>">
            <div class="content-header">
                <h2>Daftar Foto Galeri</h2>
                <a href="tambah_galeri.php" class="btn">Tambah Foto Baru</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Kegiatan</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Kegiatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_galeri->num_rows > 0): $no = 1; ?>
                        <?php while ($row = $result_galeri->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><img src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="thumbnail" width="100"></td>
                                <td><?php echo htmlspecialchars($row['nama_kegiatan']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 100)) . '...'; ?></td>
                                <td><?php echo date('d F Y', strtotime($row['tanggal_kegiatan'])); ?></td>
                                <td class="action-links">
                                    <a href="edit_galeri.php?id=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                    <a href="proses_galeri.php?aksi=hapus&id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Yakin hapus foto ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Belum ada foto di galeri.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>