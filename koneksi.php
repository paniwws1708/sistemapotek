<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sistemapotek_db";

try {
    $conn = mysqli_connect($host, $user, $pass, $db, 3307);
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
} catch (mysqli_sql_exception $e) {
    die("<div style='font-family:sans-serif; padding: 20px; background: #fee2e2; color: #dc2626; border-radius: 8px; margin: 20px; border: 1px solid #f87171;'>
            <h3>⚠️ Koneksi Database Gagal</h3>
            <p>Sepertinya server MySQL Anda belum aktif. <b>Mohon buka XAMPP Control Panel dan klik tombol \"Start\" pada modul MySQL.</b></p>
            <p><small>Detail Error: " . $e->getMessage() . "</small></p>
         </div>");
}
?>
