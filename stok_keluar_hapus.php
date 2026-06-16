<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../index.php");
    exit;
}
require '../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    mysqli_begin_transaction($conn);
    try {
        // Fetch the record to know which obat and how much stock to revert
        $q_stok = mysqli_query($conn, "SELECT id_obat, jumlah_keluar FROM stok_keluar WHERE id_stok_keluar = $id FOR UPDATE");
        if ($r_stok = mysqli_fetch_assoc($q_stok)) {
            $id_obat = $r_stok['id_obat'];
            $jumlah_keluar = $r_stok['jumlah_keluar'];
            
            // Revert stock (add back what was removed)
            mysqli_query($conn, "UPDATE obat SET stok = stok + $jumlah_keluar WHERE id_obat = $id_obat");
            
            // Delete the log
            mysqli_query($conn, "DELETE FROM stok_keluar WHERE id_stok_keluar = $id");
        }
        mysqli_commit($conn);
        $_SESSION['stok_keluar_msg'] = "<div class='alert-success'>Data stok keluar berhasil dihapus dan stok obat telah dikembalikan.</div>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['stok_keluar_msg'] = "<div class='alert-error'>⚠️ Gagal menghapus stok keluar: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

header("Location: stok_keluar.php");
exit;
?>
