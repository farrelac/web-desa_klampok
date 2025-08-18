<?php
// Panggil header
require_once 'templates/header.php';

// --- Query untuk Berita Utama (Slider) ---
$query_headline = "SELECT id, judul, ringkasan, gambar FROM berita WHERE is_headline = 1 ORDER BY tanggal_publikasi DESC LIMIT 3";
$result_headline = $conn->query($query_headline);

// --- Query untuk Berita & Pengumuman (Daftar) ---
$query_berita = "SELECT id, judul, ringkasan, tanggal_publikasi FROM berita ORDER BY tanggal_publikasi DESC LIMIT 5";
$result_berita = $conn->query($query_berita);

?>

<main>
    <!-- Hero Section: Welcome Message and Background Image -->
    <section id="beranda" class="hero fade-in active">
        <div class="container">
            <h2>Selamat Datang di Desa Klampok!</h2>
            <p>Menjelajahi Keindahan dan Potensi Desa Klampok, Kabupaten Malang.</p>
            <a href="#profil-desa" class="btn">Lihat Selengkapnya</a>
        </div>
    </section>

    <!-- NEW SECTION: Berita Utama (News Slider) -->
    <section id="berita-utama" class="section-padding bg-light fade-in">
        <div class="container">
            <h2>Berita Utama Desa Klampok</h2>
            <div class="berita-slider-container">
                <div class="berita-slider-wrapper">
                    <?php if ($result_headline->num_rows > 0): ?>
                        <?php while ($row = $result_headline->fetch_assoc()): ?>
                            <div class="berita-slide-item">
                                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                                <div class="slide-content">
                                    <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                                    <p><?php echo htmlspecialchars($row['ringkasan']); ?></p>
                                    <a href="detail_berita.php?id=<?php echo $row['id']; ?>" class="btn-small">Baca Selengkapnya</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="berita-slide-item">
                            <img src="https://placehold.co/800x450/cccccc/333333?text=Belum+Ada+Berita" alt="Belum Ada Berita">
                            <div class="slide-content">
                                <h3>Belum Ada Berita Utama</h3>
                                <p>Silakan kunjungi halaman ini lagi nanti untuk update terbaru dari Desa Klampok.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button class="slider-nav-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-nav-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                <div class="slider-dots"></div>
            </div>
        </div>
    </section>

    <!-- ============================================== -->
    <!--         PERUBAHAN BAGIAN PROFIL DESA           -->
    <!-- ============================================== -->
    <section id="profil-desa" class="section-padding fade-in">
        <div class="container">
            <h2>Profil Desa</h2>
            <div class="available-soon-container">
                <div class="available-soon-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3>Segera Hadir</h3>
                <p>Informasi lengkap mengenai Sejarah, Visi & Misi, serta Struktur Pemerintahan Desa Klampok sedang dalam tahap penyusunan. Kami berkomitmen untuk menyajikan data yang akurat dan komprehensif untuk Anda.</p>
                <p>Terima kasih atas kesabaran Anda!</p>
            </div>
        </div>
    </section>

    <!-- ============================================== -->
    <!--      PERUBAHAN BAGIAN POTENSI & KEUNGGULAN     -->
    <!-- ============================================== -->
    <section id="potensi-unggulan" class="section-padding bg-light fade-in">
        <div class="container">
            <h2>Potensi & Keunggulan Desa Klampok</h2>
            <div class="available-soon-container">
                <div class="available-soon-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Informasi Potensi Desa Segera Tersedia</h3>
                <p>Kami sedang mengumpulkan dan merangkum data mengenai Potensi Pariwisata, Produk Unggulan, dan Sumber Daya Alam yang dimiliki Desa Klampok. Halaman ini akan segera diperbarui dengan informasi yang menarik dan bermanfaat.</p>
                <p>Nantikan update dari kami!</p>
            </div>
        </div>
    </section>

    <!-- Photo & Video Gallery Section -->
    <section id="galeri" class="section-padding fade-in">
        <div class="container">
            <h2>Galeri Kegiatan Desa</h2>
            <div class="album-grid">
                <?php
                $kategori_album = [
                    'pemerintahan' => 'Kegiatan Pemerintahan Desa',
                    'pembangunan' => 'Pembangunan & Infrastruktur',
                    'kemasyarakatan' => 'Kegiatan Kemasyarakatan & Sosial',
                    'wisata_kuliner' => 'Wisata & Kuliner'
                ];
                foreach ($kategori_album as $key => $nama_album):
                    $query_thumb = "SELECT gambar FROM galeri WHERE kategori = '$key' ORDER BY tanggal_kegiatan DESC LIMIT 1";
                    $result_thumb = $conn->query($query_thumb);
                    $thumb = $result_thumb->fetch_assoc();
                    $gambar_thumb = $thumb ? 'uploads/' . $thumb['gambar'] : 'https://placehold.co/400x300/28a745/ffffff?text=Album';
                ?>
                    <a href="album.php?kategori=<?php echo $key; ?>" class="album-item">
                        <img src="<?php echo $gambar_thumb; ?>" alt="<?php echo $nama_album; ?>">
                        <div class="album-title">
                            <h3><?php echo $nama_album; ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- News and Announcements Section -->
    <section id="berita" class="section-padding bg-light fade-in">
        <div class="container">
            <h2>Berita & Pengumuman</h2>
            <div class="berita-list">
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
                    <p style="text-align: center;">Saat ini belum ada berita atau pengumuman yang dipublikasikan.</p>
                <?php endif; ?>
                <div style="text-align: center; margin-top: 40px;">
                    <a href="berita.php" class="btn">Lihat Semua Berita & Pengumuman</a>
                </div>
            </div>
        </div>
    </section>

    <!-- NEW SECTION: Saran & Masukan (Feedback Form) -->
    <section id="saran-masukan" class="section-padding fade-in">
        <div class="container">
            <h2>Kirim Saran & Masukan Anda</h2>
            <p class="text-center">Kami sangat menghargai setiap dukungan dan masukan dari Anda untuk kemajuan Desa Klampok.</p>
            <form id="feedbackFormMain" class="feedback-form">
                <div class="form-group">
                    <label for="nameMain">Nama Lengkap:</label>
                    <input type="text" id="nameMain" name="name" placeholder="Masukkan nama Anda (Opsional)">
                </div>
                <div class="form-group">
                    <label for="messageMain">Saran/Masukan:</label>
                    <textarea id="messageMain" name="message" rows="6" required placeholder="Tulis saran atau masukan Anda di sini..."></textarea>
                </div>
                <button type="submit" class="btn">Kirim Masukan</button>
                <div id="formMessageMain" class="form-message"></div>
            </form>
        </div>
    </section>

    <!-- Contact Information and Map Location Section -->
    <section id="kontak" class="section-padding fade-in">
        <div class="container">
            <h2>Kontak & Lokasi</h2>
            <div class="grid-2">
                <div class="kontak-info">
                    <h3>Kantor Desa Klampok</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Alamat: Jl. Raya Klampok Timur No.190, Krajan, Klampok, Kec. Singosari, Kabupaten Malang, Jawa Timur 65153</p>
                    <p><i class="fas fa-phone"></i> Telepon: [Nomor Telepon Desa]</p>
                    <p><i class="fas fa-envelope"></i> Email: <a href="mailto:info@desaklampok.or.id">info@desaklampok.or.id</a></p>
                    <p><i class="fas fa-clock"></i> Jam Kerja: Senin - Jumat, 08:00 - 16:00 WIB</p>
                    <p>Kami siap melayani dan menjawab pertanyaan Anda. Jangan ragu untuk menghubungi kami.</p>
                </div>
                <div class="peta-lokasi">
                    <h3>Peta Lokasi</h3>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2446.1338166148134!2d112.64994926452016!3d-7.886676819815327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd62a5b814db1df%3A0x3c41d2afe2170d27!2sKepala%20Desa%20Klampok%20Lama!5e0!3m2!1sid!2sid!4v1755419945747!5m2!1sid!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
// Panggil footer
require_once 'templates/footer.php';
?>