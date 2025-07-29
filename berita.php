<?php
// 1. Panggil header
require_once 'templates/header.php';

// 2. Logika Pagination
$artikel_per_halaman = 6; // Tentukan berapa berita yang tampil per halaman
$query_total = "SELECT COUNT(id) AS total FROM berita";
$result_total = $conn->query($query_total);
$total_artikel = $result_total->fetch_assoc()['total'];
$total_halaman = ceil($total_artikel / $artikel_per_halaman);

// Tentukan halaman aktif. Jika tidak ada di URL, defaultnya halaman 1
$halaman_aktif = (isset($_GET['halaman']) && is_numeric($_GET['halaman'])) ? (int)$_GET['halaman'] : 1;
// Jika halaman aktif lebih besar dari total halaman, kembalikan ke halaman terakhir
if ($halaman_aktif > $total_halaman && $total_halaman > 0) {
    $halaman_aktif = $total_halaman;
}
// Jika halaman aktif kurang dari 1, kembalikan ke halaman 1
if ($halaman_aktif < 1) {
    $halaman_aktif = 1;
}

$offset = ($halaman_aktif - 1) * $artikel_per_halaman;

// 3. Query untuk mengambil berita sesuai halaman
$query_berita = "SELECT id, judul, ringkasan, tanggal_publikasi FROM berita ORDER BY tanggal_publikasi DESC LIMIT $artikel_per_halaman OFFSET $offset";
$result_berita = $conn->query($query_berita);

// 4. Update Judul Tab Browser
echo "<script>document.title = 'Arsip Berita & Pengumuman - Desa Klampok';</script>";
?>

<main>
    <div class="section-padding bg-light">
        <div class="container">
            <!-- Judul Halaman Arsip -->
            <h2 class="archive-title">Berita & Pengumuman</h2>

            <!-- Daftar Berita -->
            <div class="berita-list archive-list">
                <?php if ($result_berita->num_rows > 0): ?>
                    <?php while ($row = $result_berita->fetch_assoc()): ?>
                        <article class="berita-item">
                            <h3><a href="detail_berita.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['judul']); ?></a></h3>
                            <p class="tanggal">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo date('d F Y', strtotime($row['tanggal_publikasi'])); ?>
                            </p>
                            <p><?php echo htmlspecialchars($row['ringkasan']); ?></p>
                            <a href="detail_berita.php?id=<?php echo $row['id']; ?>" class="read-more">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; grid-column: 1 / -1;">Saat ini belum ada berita atau pengumuman yang dipublikasikan.</p>
                <?php endif; ?>
            </div>

            <!-- 5. Navigasi Pagination -->
            <?php if ($total_halaman > 1): ?>
                <nav class="pagination-container">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                            <li>
                                <a href="berita.php?halaman=<?php echo $i; ?>" class="<?php if ($i == $halaman_aktif) echo 'active'; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php
// 6. Panggil footer
require_once 'templates/footer.php';
?>