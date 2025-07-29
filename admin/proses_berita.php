<?php
session_start();
// Cek sesi login
if (!isset($_SESSION['admin_logged_in'])) {
    echo "Akses ditolak. Silakan login terlebih dahulu.";
    exit();
}

require_once '../config/database.php';

// Menentukan folder upload
$upload_dir = '../uploads/';

// Mengambil parameter aksi dari URL
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// Fungsi untuk menangani upload gambar
function uploadGambar($file)
{
    global $upload_dir;

    // Jika tidak ada file yang diupload atau terjadi error
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => 'error', 'message' => 'Tidak ada gambar yang diupload atau terjadi kesalahan.'];
    }

    $nama_file = basename($file['name']);
    $tipe_file = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $nama_unik = uniqid() . '.' . $tipe_file;
    $target_file = $upload_dir . $nama_unik;

    // Cek apakah file adalah gambar
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['status' => 'error', 'message' => 'File bukan gambar.'];
    }

    // Cek tipe file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($tipe_file, $allowed_types)) {
        return ['status' => 'error', 'message' => 'Hanya format JPG, JPEG, PNG, & GIF yang diizinkan.'];
    }

    // Coba upload file
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['status' => 'success', 'filename' => $nama_unik];
    } else {
        return ['status' => 'error', 'message' => 'Gagal mengupload gambar.'];
    }
}


// ===============================
// PROSES TAMBAH BERITA
// ===============================
if ($aksi == 'tambah') {
    $judul = $_POST['judul'];
    $ringkasan = $_POST['ringkasan'];
    $isi_berita = $_POST['isi_berita'];
    // Jika checkbox dicentang, nilainya 1, jika tidak, nilainya 0
    $is_headline = isset($_POST['is_headline']) ? 1 : 0;

    // Proses upload gambar
    $upload_result = uploadGambar($_FILES['gambar']);

    if ($upload_result['status'] == 'success') {
        $nama_gambar = $upload_result['filename'];

        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO berita (judul, ringkasan, isi_berita, gambar, is_headline) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $judul, $ringkasan, $isi_berita, $nama_gambar, $is_headline);

        if ($stmt->execute()) {
            header('Location: dashboard.php?status=sukses_tambah');
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Jika upload gagal, tampilkan pesan error
        echo "Gagal menambahkan berita. Error: " . $upload_result['message'];
    }
}

// ===============================
// PROSES EDIT BERITA
// ===============================
elseif ($aksi == 'edit') {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $ringkasan = $_POST['ringkasan'];
    $isi_berita = $_POST['isi_berita'];
    $is_headline = isset($_POST['is_headline']) ? 1 : 0;

    $nama_gambar_baru = '';
    // Cek apakah ada file gambar baru yang diupload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $upload_result = uploadGambar($_FILES['gambar']);
        if ($upload_result['status'] == 'success') {
            $nama_gambar_baru = $upload_result['filename'];
        } else {
            echo "Gagal mengupdate berita. Error: " . $upload_result['message'];
            exit();
        }
    }

    if (!empty($nama_gambar_baru)) {
        // Jika ada gambar baru, update semua field termasuk gambar
        $stmt = $conn->prepare("UPDATE berita SET judul=?, ringkasan=?, isi_berita=?, gambar=?, is_headline=? WHERE id=?");
        $stmt->bind_param("ssssii", $judul, $ringkasan, $isi_berita, $nama_gambar_baru, $is_headline, $id);
    } else {
        // Jika tidak ada gambar baru, update semua field KECUALI gambar
        $stmt = $conn->prepare("UPDATE berita SET judul=?, ringkasan=?, isi_berita=?, is_headline=? WHERE id=?");
        $stmt->bind_param("sssii", $judul, $ringkasan, $isi_berita, $is_headline, $id);
    }

    if ($stmt->execute()) {
        header('Location: dashboard.php?status=sukses_edit');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// ===============================
// PROSES HAPUS BERITA
// ===============================
elseif ($aksi == 'hapus') {
    $id = $_GET['id'];

    // Ambil nama file gambar untuk dihapus dari folder
    $result = $conn->query("SELECT gambar FROM berita WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        $file_path = $upload_dir . $row['gambar'];
        if (file_exists($file_path)) {
            unlink($file_path); // Hapus file gambar
        }
    }

    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM berita WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header('Location: dashboard.php?status=sukses_hapus');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
