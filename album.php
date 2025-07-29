<?php
require_once 'templates/header.php';

// Kategori dari URL
$kategori_key = $_GET['kategori'] ?? '';

// Definisikan kategori album (untuk mengambil nama lengkap)
$kategori_album = [
    'pemerintahan' => 'Kegiatan Pemerintahan Desa',
    'pembangunan' => 'Pembangunan & Infrastruktur',
    'kemasyarakatan' => 'Kegiatan Kemasyarakatan & Sosial',
    'wisata_kuliner' => 'Wisata & Kuliner'
];

// Jika kategori tidak valid, redirect ke index
if (!array_key_exists($kategori_key, $kategori_album)) {
    header('Location: index.php#galeri');
    exit();
}

$nama_album = $kategori_album[$kategori_key];

// Query untuk mengambil semua foto dari kategori yang dipilih
$query_album = $conn->prepare("SELECT * FROM galeri WHERE kategori = ? ORDER BY tanggal_kegiatan DESC");
$query_album->bind_param("s", $kategori_key);
$query_album->execute();
$result_album = $query_album->get_result();
?>
<main>
    <section class="section-padding bg-light">
        <div class="container">
            <a href="index.php#galeri" class="back-link-album">← Kembali ke Daftar Album</a>
            <h2>Album: <?php echo $nama_album; ?></h2>
            <div class="galeri-grid">
                <?php if ($result_album->num_rows > 0): ?>
                    <?php while ($row = $result_album->fetch_assoc()): ?>
                        <div class="galeri-item">
                            <a href="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" data-lightbox="album-<?php echo $kategori_key; ?>" data-title="<?php echo htmlspecialchars($row['nama_kegiatan']); ?>">
                                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_kegiatan']); ?>">
                                <div class="galeri-caption">
                                    <h5><?php echo htmlspecialchars($row['nama_kegiatan']); ?></h5>
                                    <small><?php echo date('d F Y', strtotime($row['tanggal_kegiatan'])); ?></small>
                                </div>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center" style="grid-column: 1 / -1;">Belum ada foto dalam album ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php
require_once 'templates/footer.php';
?>
<!-- Tambahkan script untuk Lightbox2 jika ingin efek popup -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>