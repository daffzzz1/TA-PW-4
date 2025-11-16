<?php
session_start();

// Cek login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Hapus data dari array session jika ada
    if (isset($_SESSION['kontak'][$id])) {
        unset($_SESSION['kontak'][$id]);
        
        // Re-index array
        $_SESSION['kontak'] = array_values($_SESSION['kontak']);
    }
}

// Redirect kembali ke halaman utama
header("Location: login.php");
exit();
?>