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
        $q_stok = mysqli_query($conn, "SELECT id_obat, jumlah_masuk FROM stok_masuk WHERE id_stok_masuk = $id FOR UPDATE");
        if ($r_stok = mysqli_fetch_assoc($q_stok)) {
            $id_obat = $r_stok['id_obat'];
            $jumlah_masuk = $r_stok['jumlah_masuk'];
            
            // Revert stock (subtract what was added)
            mysqli_query($conn, "UPDATE obat SET stok = stok - $jumlah_masuk WHERE id_obat = $id_obat");
            
            // Delete the log
            mysqli_query($conn, "DELETE FROM stok_masuk WHERE id_stok_masuk = $id");
        }
        mysqli_commit($conn);
        $_SESSION['stok_msg'] = "<div class='alert-success'>Data stok masuk berhasil dihapus dan stok obat telah disesuaikan.</div>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['stok_msg'] = "<div class='alert-error'>⚠️ Gagal menghapus stok masuk: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

header("Location: stok_masuk.php");
exit;
?>
