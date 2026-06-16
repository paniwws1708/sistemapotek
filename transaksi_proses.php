<?php
session_start();
require '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_data = json_decode($_POST['cart_data'], true);
    $total_harga = $_POST['total_harga'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $cash_received = $_POST['cash_received'];
    $kembalian = $_POST['kembalian'];
    $tanggal_transaksi = date('Y-m-d H:i:s');
    $status_pembayaran = 'Lunas';

    if (empty($cart_data)) {
        echo "<script>alert('Keranjang kosong!'); window.location.href='../data/transaksi.php';</script>";
        exit;
    }

    mysqli_begin_transaction($conn);

    try {
        // Insert into transaksi table
        $query_transaksi = "INSERT INTO transaksi (tanggal_transaksi, total_harga, metode_pembayaran, status_pembayaran) 
                            VALUES ('$tanggal_transaksi', '$total_harga', '$metode_pembayaran', '$status_pembayaran')";
        if (!mysqli_query($conn, $query_transaksi)) {
            throw new Exception("Error inserting transaction: " . mysqli_error($conn));
        }
        
        $id_transaksi = mysqli_insert_id($conn);

        // Insert details and update stock
        foreach ($cart_data as $id_obat => $item) {
            $jumlah = $item['qty'];
            $subtotal = $item['price'] * $jumlah;

            // Insert detail
            $query_detail = "INSERT INTO detail_transaksi (id_transaksi, id_obat, jumlah, subtotal) 
                             VALUES ('$id_transaksi', '$id_obat', '$jumlah', '$subtotal')";
            if (!mysqli_query($conn, $query_detail)) {
                throw new Exception("Error inserting detail: " . mysqli_error($conn));
            }

            // Update stock
            $query_stock = "UPDATE obat SET stok = stok - $jumlah WHERE id_obat = '$id_obat'";
            if (!mysqli_query($conn, $query_stock)) {
                throw new Exception("Error updating stock: " . mysqli_error($conn));
            }

            // Log ke stok_keluar
            $tgl_keluar = date('Y-m-d');
            $ref = 'Transaksi-' . $id_transaksi;
            $query_log = "INSERT INTO stok_keluar (id_obat, tanggal_keluar, jumlah_keluar, keterangan, referensi) VALUES ('$id_obat', '$tgl_keluar', '$jumlah', 'Penjualan Kasir', '$ref')";
            if (!mysqli_query($conn, $query_log)) {
                throw new Exception("Error logging stock: " . mysqli_error($conn));
            }
        }

        mysqli_commit($conn);
        
        // Redirect to receipt printing
        echo "<script>
            window.location.href='../src/cetak_struk.php?id=$id_transaksi&cash=$cash_received&change=$kembalian';
        </script>";
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal memproses transaksi: " . $e->getMessage() . "'); window.location.href='../data/transaksi.php';</script>";
        exit;
    }
} else {
    header("Location: ../data/transaksi.php");
    exit;
}
