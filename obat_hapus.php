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
        mysqli_query($conn, "DELETE FROM obat WHERE id_obat = $id");
        $_SESSION['obat_msg'] = "<div class='alert-success'>Data obat berhasil dihapus.</div>";
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
            $_SESSION['obat_msg'] = "<div class='alert-error'>⚠️ Obat tidak bisa dihapus karena masih tercatat dalam riwayat resep, stok keluar, atau transaksi.</div>";
        } else {
            $_SESSION['obat_msg'] = "<div class='alert-error'>⚠️ Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
}

header("Location: ../data/obat.php");
exit;
?>
