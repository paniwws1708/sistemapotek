    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand" style="display: flex; justify-content: center; margin-bottom: 45px; margin-top: 10px;">
            <img src="<?= $base_path; ?>public/logo.png" alt="Logo Apotek Arisa" style="width: 140px; height: auto; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">
        </div>
        <ul class="sidebar-nav">
            <li class="<?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>"><a href="<?= $base_path; ?>dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li class="<?= ($current_page == 'pasien.php') ? 'active' : ''; ?>"><a href="<?= $base_path; ?>data/pasien.php"><i class="fa-solid fa-users"></i> Pasien</a></li>
            <li class="<?= (strpos($current_page, 'obat') !== false) ? 'active' : ''; ?>"><a href="<?= $base_path; ?>data/obat.php"><i class="fa-solid fa-pills"></i> Obat</a></li>
            <li class="<?= ($current_page == 'resep.php') ? 'active' : ''; ?>"><a href="<?= $base_path; ?>data/resep.php"><i class="fa-solid fa-file-prescription"></i> Resep</a></li>
            <li class="<?= ($current_page == 'transaksi.php') ? 'active' : ''; ?>"><a href="<?= $base_path; ?>data/transaksi.php"><i class="fa-solid fa-cart-shopping"></i> Transaksi</a></li>
            <li class="<?= ($current_page == 'stok_masuk.php') ? 'active' : ''; ?>"><a href="<?= $base_path; ?>src/stok_masuk.php"><i class="fa-solid fa-box-open"></i> Stok Masuk</a></li>
            <li class="<?= ($current_page == 'stok_keluar.php') ? 'active' : ''; ?>"><a href="<?= $base_path; ?>src/stok_keluar.php"><i class="fa-solid fa-box"></i> Stok Keluar</a></li>
        </ul>
        <div class="sidebar-footer">
            <a href="<?= $base_path; ?>logout.php" class="logout-link"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
        </div>
    </aside>
