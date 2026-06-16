<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../index.php");
    exit;
}
require '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    try {
        mysqli_query($conn, "DELETE FROM pasien WHERE id_pasien = $id");
        $_SESSION['pasien_msg'] = "<div class='alert-success'>Data pasien berhasil dihapus.</div>";
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
            $_SESSION['pasien_msg'] = "<div class='alert-error'>⚠️ Pasien tidak bisa dihapus karena masih memiliki riwayat resep atau transaksi di sistem.</div>";
        } else {
            $_SESSION['pasien_msg'] = "<div class='alert-error'>⚠️ Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}

header("Location: ../data/pasien.php");
exit;
?>
