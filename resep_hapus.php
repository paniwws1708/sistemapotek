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
        // Kembalikan stok obat dari detail resep dan hapus log
        $q_det = mysqli_query($conn, "SELECT id_detail_resep, id_obat, jumlah_obat FROM detail_resep WHERE id_resep = $id FOR UPDATE");
        if($q_det) {
            while($r_det = mysqli_fetch_assoc($q_det)) {
                mysqli_query($conn, "UPDATE obat SET stok = stok + " . $r_det['jumlah_obat'] . " WHERE id_obat = " . $r_det['id_obat']);
                
                $ref = 'Resep-Detail-' . $r_det['id_detail_resep'];
                mysqli_query($conn, "DELETE FROM stok_keluar WHERE referensi = '$ref'");
            }
        }
        
        // Hapus detail
        mysqli_query($conn, "DELETE FROM detail_resep WHERE id_resep = $id");
        
        // Hapus foto jika ada (ambil data dulu)
        $q_foto = mysqli_query($conn, "SELECT foto_resep FROM resep WHERE id_resep = $id");
        $r_foto = null;
        if($q_foto) {
            $r_foto = mysqli_fetch_assoc($q_foto);
        }
        
        // Hapus resep
        mysqli_query($conn, "DELETE FROM resep WHERE id_resep = $id");

        mysqli_commit($conn);

        // Hapus file fisik setelah commit sukses
        if($r_foto && $r_foto['foto_resep'] && file_exists('../uploads/resep/' . $r_foto['foto_resep'])) {
            unlink('../uploads/resep/' . $r_foto['foto_resep']);
        }

    } catch (Exception $e) {
        mysqli_rollback($conn);
        // Error handling bisa di-log atau alert, lalu redirect
    }
}

header("Location: ../data/resep.php");
exit;
?>
