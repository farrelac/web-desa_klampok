<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    exit('Akses ditolak.');
}

require_once '../config/database.php';
$upload_dir = '../uploads/';
$aksi = $_GET['aksi'] ?? '';

// ==========================================================
// FUNGSI UNTUK MENGHANDLE UPLOAD GAMBAR
// ==========================================================
function uploadGambar($file)
{
    global $upload_dir;

    // 1. Cek apakah ada file yang diupload dan tidak ada error
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        // Ini normal jika admin tidak memilih file baru saat edit
        return ['status' => 'no_file', 'message' => 'Tidak ada file baru yang diupload.'];
    }

    // 2. Persiapan nama file dan path
    $nama_file_asli = basename($file['name']);
    $tipe_file = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));

    // Buat nama unik untuk mencegah file tertimpa
    $nama_unik = 'galeri_' . uniqid() . '.' . $tipe_file;
    $target_file = $upload_dir . $nama_unik;

    // 3. Validasi file (apakah benar-benar gambar?)
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['status' => 'error', 'message' => 'File yang diupload bukan gambar.'];
    }

    // 4. Validasi tipe file (hanya izinkan format tertentu)
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($tipe_file, $allowed_types)) {
        return ['status' => 'error', 'message' => 'Hanya format JPG, JPEG, PNG, GIF, dan WEBP yang diizinkan.'];
    }

    // 5. Coba pindahkan file yang diupload ke folder tujuan
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        // Jika berhasil, kembalikan status sukses dan nama file uniknya
        return ['status' => 'success', 'filename' => $nama_unik];
    } else {
        // Jika gagal, kembalikan status error
        return ['status' => 'error', 'message' => 'Gagal memindahkan file yang diupload.'];
    }
}

// ==========================================================
// LOGIKA UTAMA BERDASARKAN AKSI
// ==========================================================

switch ($aksi) {
    case 'tambah':
        // Ambil data dari form, termasuk kategori
        $nama_kegiatan = $_POST['nama_kegiatan'] ?? '';
        $kategori = $_POST['kategori'] ?? ''; // <-- DATA BARU
        $deskripsi = $_POST['deskripsi'] ?? '';
        $tanggal_kegiatan = $_POST['tanggal_kegiatan'] ?? '';

        $upload_result = uploadGambar($_FILES['gambar']);

        if ($upload_result['status'] == 'success') {
            $nama_gambar = $upload_result['filename'];
            
            // PERBARUI QUERY INSERT
            $stmt = $conn->prepare("INSERT INTO galeri (nama_kegiatan, deskripsi, tanggal_kegiatan, gambar, kategori) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama_kegiatan, $deskripsi, $tanggal_kegiatan, $nama_gambar, $kategori);
            
            if ($stmt->execute()) {
                header('Location: dashboard.php?tab=galeri&status=sukses_tambah_galeri');
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Gagal: " . $upload_result['message'];
        }
        break;

    case 'edit':
        $id = $_POST['id'] ?? 0;
        $gambar_lama = $_POST['gambar_lama'] ?? '';
        $nama_kegiatan = $_POST['nama_kegiatan'] ?? '';
        $kategori = $_POST['kategori'] ?? ''; // <-- DATA BARU
        $deskripsi = $_POST['deskripsi'] ?? '';
        $tanggal_kegiatan = $_POST['tanggal_kegiatan'] ?? '';

        // ... (logika upload gambar baru tetap sama) ...
        $nama_gambar_baru = '';
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) { /* ... */ }

        if (!empty($nama_gambar_baru)) {
            // PERBARUI QUERY UPDATE (DENGAN GAMBAR BARU)
            $stmt = $conn->prepare("UPDATE galeri SET nama_kegiatan=?, deskripsi=?, tanggal_kegiatan=?, gambar=?, kategori=? WHERE id=?");
            $stmt->bind_param("sssssi", $nama_kegiatan, $deskripsi, $tanggal_kegiatan, $nama_gambar_baru, $kategori, $id);
        } else {
            // PERBARUI QUERY UPDATE (TANPA GAMBAR BARU)
            $stmt = $conn->prepare("UPDATE galeri SET nama_kegiatan=?, deskripsi=?, tanggal_kegiatan=?, kategori=? WHERE id=?");
            $stmt->bind_param("ssssi", $nama_kegiatan, $deskripsi, $tanggal_kegiatan, $kategori, $id);
        }

        if ($stmt->execute()) {
            header('Location: dashboard.php?tab=galeri&status=sukses_edit_galeri');
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        break;

    // ---------- KASUS 3: HAPUS DATA ----------
    case 'hapus':
        $id = $_GET['id'] ?? 0;

        // Ambil nama file gambar untuk dihapus dari folder
        $result = $conn->query("SELECT gambar FROM galeri WHERE id = $id");
        if ($row = $result->fetch_assoc()) {
            $file_path = $upload_dir . $row['gambar'];
            if (file_exists($file_path)) {
                unlink($file_path); // Hapus file gambar dari server
            }
        }

        // Hapus data dari database
        $stmt = $conn->prepare("DELETE FROM galeri WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Jika berhasil, redirect ke dashboard dengan pesan sukses
            header('Location: dashboard.php?tab=galeri&status=sukses_hapus_galeri');
        } else {
            echo "Error saat menghapus data: " . $stmt->error;
        }
        $stmt->close();
        break;

    // ---------- KASUS DEFAULT (JIKA AKSI TIDAK DIKENALI) ----------
    default:
        // Jika aksi tidak valid, kembalikan ke dashboard
        header('Location: dashboard.php');
        break;
}

// Tutup koneksi database di akhir skrip
$conn->close();
