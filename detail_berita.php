<?php
require_once 'templates/header.php';

// FUNGSI BARU UNTUK MENAMPILKAN KOMENTAR
function tampilkan_komentar($parent_id, $semua_komentar, $id_berita)
{
    $html = '';
    $komentar_anak = array_filter($semua_komentar, fn($k) => $k['parent_id'] == $parent_id);

    if (count($komentar_anak) > 0) {
        // Tentukan kelas CSS berdasarkan level kedalaman
        $ul_class = ($parent_id !== NULL) ? 'comment-replies' : 'comment-list';
        $html .= "<ul class='$ul_class'>";

        foreach ($komentar_anak as $komen) {
            $nama_tampil = !empty($komen['nama']) ? htmlspecialchars($komen['nama']) : 'Anonim';
            $avatar_initial = strtoupper(substr($nama_tampil, 0, 1));

            $html .= "<li class='comment-item' id='comment-{$komen['id']}'>";
            $html .= "<div class='comment-wrapper'>";

            // Kolom Avatar
            $html .= "<div class='comment-avatar'><span>{$avatar_initial}</span></div>";

            // Kolom Konten Komentar
            $html .= "<div class='comment-content'>";
            $html .= "<div class='comment-header'>";
            $html .= "<span class='comment-author'>{$nama_tampil}</span>";
            $html .= "<span class='comment-date'>" . date('d F Y, H:i', strtotime($komen['tanggal_komentar'])) . "</span>";
            $html .= "</div>";
            $html .= "<div class='comment-text'>" . nl2br(htmlspecialchars($komen['komentar'])) . "</div>";

            // Aksi Komentar (Balas)
            $html .= "<div class='comment-actions'>";
            $html .= "<a href='#' class='reply-btn' data-comment-id='{$komen['id']}'><i class='fas fa-reply'></i> Balas</a>";
            $html .= "</div>";

            // Form Balasan (tersembunyi)
            $html .= "<div class='reply-form-container' id='reply-form-{$komen['id']}' style='display:none;'>";
            $html .= "<form action='proses_komentar.php' method='POST'>";
            $html .= "<input type='hidden' name='berita_id' value='{$id_berita}'>";
            $html .= "<input type='hidden' name='parent_id' value='{$komen['id']}'>";
            $html .= "<textarea name='komentar' placeholder='Tulis balasan untuk {$nama_tampil}...' rows='3' required></textarea>";
            $html .= "<button type='submit' class='btn btn-small'>Kirim Balasan</button>";
            $html .= "</form>";
            $html .= "</div>";

            $html .= "</div>"; // Tutup .comment-content
            $html .= "</div>"; // Tutup .comment-wrapper

            // Panggil rekursif untuk balasan
            $html .= tampilkan_komentar($komen['id'], $semua_komentar, $id_berita);

            $html .= "</li>";
        }
        $html .= "</ul>";
    }
    return $html;
}

$berita = null;
$semua_komentar_arr = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_berita = (int)$_GET['id'];
    if (isset($conn) && $conn instanceof mysqli) {
        $stmt_berita = $conn->prepare("SELECT * FROM berita WHERE id = ?");
        $stmt_berita->bind_param("i", $id_berita);
        $stmt_berita->execute();
        $result_berita = $stmt_berita->get_result();
        $berita = $result_berita->fetch_assoc();
        $stmt_berita->close();

        if ($berita) {
            $stmt_komentar = $conn->prepare("SELECT * FROM komentar WHERE berita_id = ? ORDER BY tanggal_komentar ASC");
            $stmt_komentar->bind_param("i", $id_berita);
            $stmt_komentar->execute();
            $result_komentar = $stmt_komentar->get_result();
            $semua_komentar_arr = $result_komentar->fetch_all(MYSQLI_ASSOC);
            $stmt_komentar->close();
            echo "<script>document.title = '" . htmlspecialchars($berita['judul']) . " - Desa Klampok';</script>";
        }
    }
}
?>

<main>
    <div class="article-detail-container section-padding">
        <div class="container">
            <?php if ($berita): ?>
                <article class="berita-detail-item">
                    <h1><?php echo htmlspecialchars($berita['judul']); ?></h1>
                    <div class="meta-info">
                        <span class="tanggal"><i class="far fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($berita['tanggal_publikasi'])); ?></span>
                        <span class="author"><i class="fas fa-user"></i> Oleh: Admin Desa</span>
                    </div>
                    <img src="uploads/<?php echo htmlspecialchars($berita['gambar']); ?>" alt="<?php echo htmlspecialchars($berita['judul']); ?>" class="featured-image">
                    <div class="article-content">
                        <?php echo nl2br(htmlspecialchars($berita['isi_berita'])); ?>
                    </div>
                </article>

                <!-- ============================================= -->
                <!--     BAGIAN KOMENTAR YANG DIROMBAK TOTAL     -->
                <!-- ============================================= -->
                <section id="kolom-komentar" class="comment-section-container">
                    <?php
                    // Hitung jumlah komentar utama (bukan balasan)
                    $jumlah_komentar_utama = count(array_filter($semua_komentar_arr, fn($k) => $k['parent_id'] == NULL));
                    ?>
                    <h3><?php echo $jumlah_komentar_utama; ?> Komentar</h3>

                    <!-- Form Komentar Utama (DENGAN KOLOM NAMA) -->
                    <div class="comment-form-main">
                        <div class="comment-avatar"><span>A</span></div>
                        <form action="proses_komentar.php" method="POST">
                            <input type="hidden" name="berita_id" value="<?php echo $berita['id']; ?>">
                            <input type="hidden" name="parent_id" value="">

                            <!-- ==== PERBAIKAN DI SINI: Tambahkan input untuk nama ==== -->
                            <div class="form-group-inline">
                                <input type="text" name="nama" placeholder="Nama Lengkap Anda..." required>
                            </div>

                            <textarea name="komentar" rows="1" placeholder="Tulis komentar Anda di sini..." required oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"></textarea>

                            <button type="submit" class="btn">Kirim Komentar</button>
                        </form>
                    </div>

                    <!-- Daftar Komentar -->
                    <div class="comments-list-container">
                        <?php
                        $daftar_komentar_html = tampilkan_komentar(NULL, $semua_komentar_arr, $berita['id']);
                        if (empty($daftar_komentar_html)) {
                            echo "<p class='text-center'>Jadilah yang pertama berkomentar!</p>";
                        } else {
                            echo $daftar_komentar_html;
                        }
                        ?>
                    </div>
                </section>
                <!-- ============================================= -->
                <!--           AKHIR BAGIAN KOMENTAR            -->
                <!-- ============================================= -->


                <div style="text-align: center; margin-top: 40px;">
                    <a href="index.php" class="btn">Kembali Ke Halaman Utama</a>
                </div>
            <?php else: ?>
                <div style="text-align: center;">
                    <h2>Berita Tidak Ditemukan</h2>
                    <p>Maaf, berita yang Anda cari tidak ada atau telah dihapus.</p>
                    <a href="index.php" class="btn">Kembali Ke Halaman Utama</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
require_once 'templates/footer.php';
?>