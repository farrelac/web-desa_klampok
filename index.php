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
    <!-- The 'fade-in' class will be used by JavaScript for scroll animations -->
    <section id="beranda" class="hero fade-in active">
        <div class="container">
            <h2>Selamat Datang di Desa Klampok!</h2>
            <p>Menjelajahi Keindahan dan Potensi Desa Klampok, Kota Malang.</p>
            <a href="#profil-desa" class="btn">Lihat Lebih Lengkap</a>
        </div>
    </section>

    <!-- NEW SECTION: Berita Utama (News Slider) -->
    <section id="berita-utama" class="section-padding bg-light fade-in">
        <div class="container">
            <h2>Berita Utama Desa Klampok</h2>
            <div class="berita-slider-container">
                <div class="berita-slider-wrapper">

                    <!-- PHP Loop untuk menampilkan Berita Utama dari Database -->
                    <?php if ($result_headline->num_rows > 0): ?>
                        <?php while ($row = $result_headline->fetch_assoc()): ?>
                            <div class="berita-slide-item">
                                <!-- Gunakan gambar dari folder uploads -->
                                <img src="uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                                <div class="slide-content">
                                    <h3><?php echo htmlspecialchars($row['judul']); ?></h3>
                                    <p><?php echo htmlspecialchars($row['ringkasan']); ?></p>
                                    <!-- Nanti bisa diarahkan ke halaman detail, untuk sekarang # -->
                                    <a href="detail_berita.php?id=<?php echo $row['id']; ?>" class="btn-small">Baca Selengkapnya</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- Pesan jika tidak ada berita utama -->
                        <div class="berita-slide-item">
                            <img src="https://placehold.co/800x450/cccccc/333333?text=Belum+Ada+Berita" alt="Belum Ada Berita">
                            <div class="slide-content">
                                <h3>Belum Ada Berita Utama</h3>
                                <p>Silakan kunjungi halaman ini lagi nanti untuk update terbaru dari Desa Klampok.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Akhir dari PHP Loop -->

                </div>
                <button class="slider-nav-btn prev-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="slider-nav-btn next-btn"><i class="fas fa-chevron-right"></i></button>
                <div class="slider-dots">
                    <!-- Dots akan digenerate oleh JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <!-- Village Profile Section: History, Vision & Mission, Organizational Structure, Demographics -->
    <section id="profil-desa" class="section-padding fade-in">
        <div class="container">
            <h2>Profil Desa</h2>
            <div class="profil-desa-grid">
                <div class="profil-item">
                    <h3>Sejarah Singkat</h3>
                    <p>Desa Klampok, yang terletak di Kota Malang, memiliki akar sejarah yang dalam, mencerminkan
                        perjalanan panjang masyarakatnya dalam membentuk identitas desa. Berawal dari pemukiman
                        sederhana, Klampok berkembang menjadi pusat kegiatan pertanian dan sosial. Nama "Klampok"
                        sendiri dipercaya berasal dari...</p>
                    <p>Seiring waktu, desa ini telah menyaksikan berbagai perubahan dan perkembangan, namun tetap
                        mempertahankan nilai-nilai luhur dan kearifan lokal yang diwariskan turun-temurun.</p>
                    <!-- Detailed history will be added here by the client -->
                </div>
                <div class="profil-item">
                    <h3>Visi & Misi</h3>
                    <h4>Visi:</h4>
                    <p>Mewujudkan Desa Klampok yang mandiri, sejahtera, berbudaya, dan berkelanjutan melalui tata
                        kelola pemerintahan yang baik dan partisipasi aktif masyarakat.</p>
                    <h4>Misi:</h4>
                    <ul>
                        <li>Meningkatkan kualitas sumber daya manusia melalui pendidikan dan pelatihan.</li>
                        <li>Mengembangkan potensi ekonomi lokal berbasis pertanian, UMKM, dan pariwisata.</li>
                        <li>Melestarikan dan mengembangkan seni, budaya, serta tradisi lokal.</li>
                        <li>Meningkatkan infrastruktur dasar dan fasilitas umum yang memadai.</li>
                        <li>Menciptakan lingkungan yang bersih, sehat, dan lestari.</li>
                        <li>Mendorong partisipasi aktif masyarakat dalam pembangunan desa.</li>
                    </ul>
                </div>
                <div class="profil-item">
                    <h3>Struktur Organisasi Pemerintahan Desa</h3>
                    <p>Pemerintahan Desa Klampok dipimpin oleh Kepala Desa dan dibantu oleh perangkat desa yang
                        berdedikasi untuk melayani masyarakat. Berikut adalah struktur dasar organisasi kami:</p>
                    <ul>
                        <li><strong>Kepala Desa:</strong> [Nama Kepala Desa]</li>
                        <li><strong>Sekretaris Desa:</strong> [Nama Sekretaris Desa]</li>
                        <li><strong>Kepala Urusan (Kaur):</strong>
                            <ul>
                                <li>Kaur Tata Usaha dan Umum: [Nama Kaur]</li>
                                <li>Kaur Keuangan: [Nama Kaur]</li>
                                <li>Kaur Perencanaan: [Nama Kaur]</li>
                            </ul>
                        </li>
                        <li><strong>Kepala Seksi (Kasi):</strong>
                            <ul>
                                <li>Kasi Pemerintahan: [Nama Kasi]</li>
                                <li>Kasi Kesejahteraan: [Nama Kasi]</li>
                                <li>Kasi Pelayanan: [Nama Kasi]</li>
                            </ul>
                        </li>
                        <li><strong>Kepala Dusun:</strong> [Daftar Nama Kepala Dusun]</li>
                    </ul>
                    <!-- You can add an image of the organizational structure here if available -->
                    <img src="https://placehold.co/400x200/cccccc/333333?text=Struktur+Organisasi"
                        alt="Struktur Organisasi Desa Klampok"
                        style="max-width: 100%; height: auto; margin-top: 15px; border-radius: 5px;">
                </div>
                <div class="profil-item">
                    <h3>Data Demografi</h3>
                    <p>Desa Klampok adalah rumah bagi komunitas yang beragam dan dinamis. Data demografi berikut
                        memberikan gambaran singkat tentang populasi dan main livelihoods in our village:</p>
                    <ul>
                        <li><strong>Jumlah Penduduk:</strong> [Jumlah Jiwa] jiwa (per [Tahun Data])</li>
                        <li><strong>Jumlah Kepala Keluarga:</strong> [Jumlah KK] KK</li>
                        <li><strong>Pembagian Jenis Kelamin:</strong>
                            <ul>
                                <li>Laki-laki: [Jumlah Laki-laki] jiwa</li>
                                <li>Perempuan: [Jumlah Perempuan] jiwa</li>
                            </ul>
                        </li>
                        <li><strong>Mata Pencarian Utama:</strong>
                            <ul>
                                <li>Pertanian (Padi, Jagung, Sayuran)</li>
                                <li>Perkebunan (Kopi, Cengkeh)</li>
                                <li>Peternakan (Unggas, Kambing)</li>
                                <li>Perdagangan (Warung, Toko Kelontong)</li>
                                <li>Jasa (Transportasi, Bengkel, Salon)</li>
                                <li>Industri Rumahan (Kerajinan, Olahan Makanan)</li>
                            </ul>
                        </li>
                        <li><strong>Tingkat Pendidikan:</strong>
                            <ul>
                                <li>SD/Sederajat: [Persentase]%</li>
                                <li>SMP/Sederajat: [Persentase]%</li>
                                <li>SMA/Sederajat: [Persentase]%</li>
                                <li>Perguruan Tinggi: [Persentase]%</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Village Potential and Advantages Section -->
    <section id="potensi-unggulan" class="section-padding bg-light fade-in">
        <div class="container">
            <h2>Potensi & Keunggulan Desa Klampok</h2>
            <div class="grid-3">
                <div class="potensi-item">
                    <i class="fas fa-mountain"></i> <!-- Tourism Icon -->
                    <h3>Pariwisata</h3>
                    <p>Desa Klampok memiliki potensi wisata alam yang menawan dan budaya yang kaya. Destinasi yang
                        bisa dikembangkan meliputi:</p>
                    <ul>
                        <li><strong>Air Terjun Tirto Mulyo:</strong> Keindahan alam dengan air terjun yang jernih.
                        </li>
                        <li><strong>Perkebunan Kopi Rakyat:</strong> Pengalaman agrowisata memetik kopi dan
                            menikmati kopi lokal.</li>
                        <li><strong>Sentra Kerajinan Bambu:</strong> Melihat proses pembuatan dan membeli produk
                            kerajinan bambu.</li>
                    </ul>
                    <!-- <a href="#" class="btn-small">Lihat Detail</a> -->
                </div>
                <div class="potensi-item">
                    <i class="fas fa-boxes"></i> <!-- Featured Products Icon -->
                    <h3>Produk Unggulan</h3>
                    <p>Produk-produk khas Desa Klampok yang menjadi kebanggaan dan memiliki nilai ekonomi tinggi:
                    </p>
                    <ul>
                        <li><strong>Kopi Klampok:</strong> Kopi robusta berkualitas tinggi dengan cita rasa khas.
                        </li>
                        <li><strong>Kerajinan Anyaman Bambu:
                            </strong> Berbagai produk anyaman seperti tas, topi, dan dekorasi rumah.</li>
                        <li><strong>Olahan Singkong:</strong> Keripik singkong, getuk, dan produk olahan singkong
                            lainnya.</li>
                        <li><strong>Madu Hutan Klampok:</strong> Madu murni dari hasil budidaya lebah di hutan
                            sekitar desa.</li>
                    </ul>
                    <!-- <a href="#" class="btn-small">Lihat Detail</a> -->
                </div>
                <div class="potensi-item">
                    <i class="fas fa-tree"></i> <!-- Natural Resources Icon -->
                    <h3>Sumber Daya Alam</h3>
                    <p>Kekayaan sumber daya alam Desa Klampok menjadi tulang punggung perekonomian dan kehidupan
                        masyarakat:</p>
                    <ul>
                        <li><strong>Lahan Pertanian Subur:</strong> Cocok untuk padi, jagung, sayuran, dan
                            buah-buahan.</li>
                        <li><strong>Sumber Mata Air Bersih:</strong> Memenuhi kebutuhan air bersih warga dan
                            pengairan sawah.</li>
                        <li><strong>Hutan Rakyat:</strong> Menyediakan kayu, hasil hutan non-kayu, dan menjaga
                            ekosistem.</li>
                        <li><strong>Potensi Perikanan Darat:</strong> Budidaya ikan air tawar di kolam-kolam warga.
                        </li>
                    </ul>
                    <!-- <a href="#" class="btn-small">Lihat Detail</a> -->
                </div>
                <div class="potensi-item">
                    <i class="fas fa-masks-theater"></i> <!-- Culture & Tradition Icon -->
                    <h3>Budaya & Tradisi</h3>
                    <p>Desa Klampok kaya akan warisan budaya dan tradisi yang terus dilestarikan:</p>
                    <ul>
                        <li><strong>Kesenian Tradisional:</strong> Jaranan, Bantengan, dan Reog Ponorogo yang sering
                            dipentaskan dalam acara desa.</li>
                        <li><strong>Upacara Adat Bersih Desa:</strong> Tradisi tahunan sebagai wujud syukur dan doa
                            keselamatan.</li>
                        <li><strong>Kuliner Tradisional:</strong> Berbagai masakan khas yang diwariskan
                            turun-temurun.</li>
                        <li><strong>Gotong Royong:</strong> Semangat kebersamaan dalam setiap kegiatan pembangunan
                            desa.</li>
                    </ul>
                    <!-- <a href="#" class="btn-small">Lihat Detail</a> -->
                </div>
                <div class="potensi-item">
                    <i class="fas fa-handshake"></i> <!-- Investment Potential Icon -->
                    <h3>Potensi Investasi</h3>
                    <p>Desa Klampok membuka peluang investasi bagi pihak yang ingin berkontribusi dalam pengembangan
                        desa:</p>
                    <ul>
                        <li><strong>Pengembangan Homestay/Penginapan:</strong> Mendukung sektor pariwisata.</li>
                        <li><strong>Agrowisata Terpadu:</strong> Mengembangkan perkebunan menjadi destinasi wisata
                            edukasi.</li>
                        <li><strong>Industri Pengolahan Hasil Pertanian:</strong> Meningkatkan nilai tambah produk
                            lokal.</li>
                        <li><strong>Pengembangan Ekowisata:</strong> Memanfaatkan potensi alam secara berkelanjutan.
                        </li>
                    </ul>
                    <!-- <a href="#" class="btn-small">Lihat Detail</a> -->
                </div>
            </div>
        </div>
    </section>

    <!-- Photo & Video Gallery Section -->
    <section id="galeri" class="section-padding fade-in">
        <div class="container">
            <h2>Galeri Kegiatan Desa</h2>
            <div class="album-grid">
                <?php
                // Definisikan kategori album (HARUS SAMA dengan di form admin)
                $kategori_album = [
                    'pemerintahan' => 'Kegiatan Pemerintahan Desa',
                    'pembangunan' => 'Pembangunan & Infrastruktur',
                    'kemasyarakatan' => 'Kegiatan Kemasyarakatan & Sosial',
                    'wisata_kuliner' => 'Wisata & Kuliner'
                ];

                foreach ($kategori_album as $key => $nama_album):
                    // Ambil 1 gambar terbaru dari setiap kategori sebagai thumbnail
                    $query_thumb = "SELECT gambar FROM galeri WHERE kategori = '$key' ORDER BY tanggal_kegiatan DESC LIMIT 1";
                    $result_thumb = $conn->query($query_thumb);
                    $thumb = $result_thumb->fetch_assoc();
                    // Jika tidak ada gambar, gunakan placeholder
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

                <!-- PHP Loop untuk menampilkan Daftar Berita dari Database -->
                <?php if ($result_berita->num_rows > 0): ?>
                    <?php while ($row = $result_berita->fetch_assoc()): ?>
                        <article class="berita-item">
                            <h3><a href="detail_berita.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['judul']); ?></a></h3>
                            <p class="tanggal">
                                <i class="far fa-calendar-alt"></i>
                                <!-- Format tanggal menjadi lebih ramah dibaca -->
                                <?php echo date('d F Y', strtotime($row['tanggal_publikasi'])); ?>
                            </p>
                            <p><?php echo htmlspecialchars($row['ringkasan']); ?></p>
                            <a href="detail_berita.php?id=<?php echo $row['id']; ?>" class="read-more">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center;">Saat ini belum ada berita atau pengumuman yang dipublikasikan.</p>
                <?php endif; ?>
                <!-- Akhir dari PHP Loop -->

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
            <p class="text-center">Kami sangat menghargai setiap dukungan dan masukan dari Anda untuk kemajuan Desa
                Klampok.</p>

            <!-- Form ini akan dihubungkan ke proses_feedback.php -->
            <form id="feedbackFormMain" class="feedback-form">
                <div class="form-group">
                    <label for="nameMain">Nama Lengkap:</label>
                    <!-- Nama bisa dibuat opsional dengan menghapus atribut 'required' -->
                    <input type="text" id="nameMain" name="name" placeholder="Masukkan nama Anda (Opsional)">
                </div>

                <!-- Kolom Email dihapus sesuai permintaan -->

                <div class="form-group">
                    <label for="messageMain">Saran/Masukan:</label>
                    <textarea id="messageMain" name="message" rows="6" required
                        placeholder="Tulis saran atau masukan Anda di sini..."></textarea>
                </div>

                <button type="submit" class="btn">Kirim Masukan</button>

                <!-- Elemen untuk menampilkan pesan status (sukses/gagal) -->
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
                    <p><i class="fas fa-map-marker-alt"></i> Alamat: [Alamat Lengkap Kantor Desa Klampok], Kecamatan
                        [Nama Kecamatan], Kota Malang, Jawa Timur</p>
                    <p><i class="fas fa-phone"></i> Telepon: [Nomor Telepon Desa]</p>
                    <p><i class="fas fa-envelope"></i> Email: <a
                            href="mailto:info@desaklampok.or.id">info@desaklampok.or.id</a></p>
                    <p><i class="fas fa-clock"></i> Jam Kerja: Senin - Jumat, 08:00 - 16:00 WIB</p>
                    <p>Kami siap melayani dan menjawab pertanyaan Anda. Jangan ragu untuk menghubungi kami.</p>
                </div>
                <div class="peta-lokasi">
                    <h3>Peta Lokasi</h3>
                    <!-- Embed Google Maps for Desa Klampok location -->
                    <!-- Replace the iframe src with the accurate Google Maps embed code for Desa Klampok -->
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.791559992019!2d112.5857218147775!3d-7.925525994276707!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd629472f7e71f9%3A0x6b2e1c9e8e2b8b9!2sDesa%20Klampok!5e0!3m2!1sid!2sid!4v1678901234567!5m2!1sid!2sid"
                        width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <p class="note">Make sure to replace the `src` attribute of the iframe above with the accurate
                        Google Maps embed code for Desa Klampok.</p>
                </div>
            </div>
        </div>
    </section>
</main>


<?php
// Panggil footer
require_once 'templates/footer.php';
?>