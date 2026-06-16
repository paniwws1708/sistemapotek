<?php
require '../config/koneksi.php';

// Ambil ID dari URL
$id_transaksi = $_GET['id'] ?? '';

if ($id_transaksi) {
    mysqli_begin_transaction($conn);
    try {
        // Kembalikan stok obat dari detail transaksi
        $q_det = mysqli_query($conn, "SELECT id_obat, jumlah FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' FOR UPDATE");
        if($q_det) {
            while($r_det = mysqli_fetch_assoc($q_det)) {
                mysqli_query($conn, "UPDATE obat SET stok = stok + " . $r_det['jumlah'] . " WHERE id_obat = " . $r_det['id_obat']);
            }
        }
        
        // Hapus log stok_keluar
        $ref = 'Transaksi-' . $id_transaksi;
        mysqli_query($conn, "DELETE FROM stok_keluar WHERE referensi = '$ref'");
        
        // Hapus detail transaksi
        mysqli_query($conn, "DELETE FROM detail_transaksi WHERE id_transaksi = '$id_transaksi'");
        
        // Hapus transaksi
        mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = '$id_transaksi'");
        
        mysqli_commit($conn);

        echo "<script>
                alert('Data transaksi berhasil dihapus dan stok dikembalikan!');
                window.location.href='../data/transaksi_riwayat.php';
              </script>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>
                alert('Gagal menghapus data transaksi.');
                window.location.href='../data/transaksi_riwayat.php';
              </script>";
    }
} else {
    header("Location: ../data/transaksi_riwayat.php");
}
?>