<?php

$current_page = basename($_SERVER['PHP_SELF']);

?>

<div class="sidebar">

    <div class="brand">
        Kasir App
    </div>

    <ul>

        <a href="../pages/dashboard.php" class="sidebar-link">
            <li class="<?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                Dashboard
            </li>
        </a>

        <a href="../pages/barang.php" class="sidebar-link">
            <li class="<?= ($current_page == 'barang.php') ? 'active' : ''; ?>">
                Barang
            </li>
        </a>

        <a href="../pages/kategori.php" class="sidebar-link">
            <li class="<?= ($current_page == 'kategori.php') ? 'active' : ''; ?>">
                Kategori
            </li>
        </a>

        <a href="../pages/transaksi.php" class="sidebar-link">
            <li class="<?= ($current_page == 'transaksi.php') ? 'active' : ''; ?>">
                Transaksi
            </li>
        </a>

        <a href="../pages/laporan.php" class="sidebar-link">
            <li class="<?= ($current_page == 'laporan.php') ? 'active' : ''; ?>">
                Laporan
            </li>
        </a>

    </ul>

</div>