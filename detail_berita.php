<?php
// Panggil header. Diasumsikan 'config/database.php' sudah dipanggil di sini.
require_once 'templates/header.php';

// Fungsi untuk menampilkan komentar (VERSI STABIL, TANPA AVATAR)
function tampilkan_komentar($parent_id, $semua_komentar, $id_berita)
{
    $html = '';
    $komentar_anak = array_filter($semua_komentar, fn($k) => $k['parent_id'] == $parent_id);
    if (count($komentar_anak) > 0) {
        $html .= ($parent_id !== NULL) ? "<div class='comment-replies'>" : "<ul class='comment-list'>";
        
        foreach ($komentar_anak as $komen) {
            $nama_tampil = isset($komen['nama']) ? htmlspecialchars($komen['nama']) : 'Anonim';
            
            $html .= "<li class='comment-item' id='comment-{$komen['id']}'>";
            $html .= "<div class='comment-body'>";
            $html .= "<div class='comment-author'>{$nama_tampil}</div>";
            $html .= "<div class='comment-date'>" . date('d F Y, H:i', strtotime($komen['tanggal_komentar'])) . "</div>";
            $html .= "<div class='comment-text'>" . nl2br(htmlspecialchars($komen['komentar'])) . "</div>";
            $html .= "<a href='#' class='reply-btn' data-comment-id='{$komen['id']}'>Balas</a>";
            $html .= "</div>";

            $html .= "<div class='reply-form-container' id='reply-form-{$komen['id']}' style='display:none;'><form action='proses_komentar.php' method='POST'><input type='hidden' name='berita_id' value='{$id_berita}'><input type='hidden' name='parent_id' value='{$komen['id']}'><div class='form-group'><textarea name='komentar' placeholder='Tulis balasan...' rows='3' required></textarea></div><button type='submit' class='btn-small'>Kirim Balasan</button></form></div>";
            
            $html .= tampilkan_komentar($komen['id'], $semua_komentar, $id_berita);
            
            $html .= "</li>";
        }
        $html .= ($parent_id !== NULL) ? "</div>" : "</ul>";
    }
    return $html;
}

// ALUR UTAMA HALAMAN
$berita = null;
$semua_komentar_arr = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_berita = (int)$_GET['id'];
    
    // --- PERBAIKAN DI SINI ---
    // Baris "$conn = require 'config/database.php';" DIHAPUS.
    // Kita langsung gunakan variabel $conn yang sudah ada dari header.php
    
    // Pastikan koneksi ada sebelum melanjutkan
    if (isset($conn) && $conn instanceof mysqli) {
        $stmt_berita = $conn->prepare("SELECT * FROM berita WHERE id = ?"); // Ini baris 44 yang error
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
    } else {
        // Tampilkan pesan error jika koneksi tidak ditemukan
        echo "Koneksi database gagal.";
    }
}
?>

<main>
    <div class="section-padding">
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

                <section id="kolom-komentar" class="comment-section-container">
                    <div class="comment-section">
                        <h3>Komentar</h3>
                        <div class="comment-form">
                            <h4>Tinggalkan Komentar</h4>
                            <form action="proses_komentar.php" method="POST">
                                <input type="hidden" name="berita_id" value="<?php echo $berita['id']; ?>">
                                <input type="hidden" name="parent_id" value="">
                                <div class="form-group">
                                    <textarea name="komentar" rows="4" placeholder="Tulis komentar Anda di sini..." required></textarea>
                                </div>
                                <button type="submit" class="btn">Kirim Komentar</button>
                            </form>
                        </div>
                        <div class="comments-list-container">
                            <?php
                            $daftar_komentar_html = tampilkan_komentar(NULL, $semua_komentar_arr, $berita['id']);
                            if (empty($daftar_komentar_html)) {
                                echo "<p>Jadilah yang pertama berkomentar!</p>";
                            } else {
                                echo $daftar_komentar_html;
                            }
                            ?>
                        </div>
                    </div>
                </section>

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
// Panggil footer
require_once 'templates/footer.php';
?>