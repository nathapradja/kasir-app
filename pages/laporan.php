<?php include "../config/auth.php"; ?>
<?php include "../partials/header.php"; ?>
<?php include "../config/database.php"; ?>

<?php

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = '';

if($tanggal_awal && $tanggal_akhir){

    $where = "
        WHERE DATE(created_at)
        BETWEEN '$tanggal_awal'
        AND '$tanggal_akhir'
    ";
}

/* =========================
   QUERY DATA TRANSAKSI
========================= */

$query = "
    SELECT *
    FROM transaksi
    $where
    ORDER BY id DESC
";

$result = pg_query($conn, $query);

/* =========================
   TOTAL PENDAPATAN
========================= */

$query_total = "
    SELECT SUM(total) as total_pendapatan
    FROM transaksi
    $where
";

$result_total = pg_query($conn, $query_total);

$data_total = pg_fetch_assoc($result_total);

$total_pendapatan = $data_total['total_pendapatan'] ?? 0;

?>

<?php include "../partials/sidebar.php"; ?>

<div class="main-content">

    <?php include "../partials/navbar.php"; ?>

    <div class="content">

        <!-- PAGE HEADER -->

        <div class="page-header">

            <h1>Laporan Penjualan</h1>

        </div>

        <!-- FILTER -->

        <div class="form-card laporan-filter">

            <form method="GET">

                <div class="form-group">

                    <label>Tanggal Awal</label>

                    <input
                        type="date"
                        name="tanggal_awal"
                        value="<?= $tanggal_awal; ?>"
                    >

                </div>

                <div class="form-group">

                    <label>Tanggal Akhir</label>

                    <input
                        type="date"
                        name="tanggal_akhir"
                        value="<?= $tanggal_akhir; ?>"
                    >

                </div>

                <button type="submit">

                    Filter

                </button>

                <a
                    href="print-laporan.php?tanggal_awal=<?= $tanggal_awal; ?>&tanggal_akhir=<?= $tanggal_akhir; ?>"
                    target="_blank"
                    class="print-btn"
                >

                    Print

                </a>

            </form>

        </div>

        <!-- TOTAL PENDAPATAN -->

        <div class="cards">

            <div class="card">

                <h3>Total Pendapatan</h3>

                <p>

                    Rp <?= number_format($total_pendapatan); ?>

                </p>

            </div>

        </div>

        <!-- TABLE -->

        <div class="table-card mt-24">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Total</th>
                        <th>Bayar</th>
                        <th>Kembalian</th>
                        <th>Tanggal</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while($row = pg_fetch_assoc($result)) : ?>

                        <tr>

                            <td>
                                <?= $row['id']; ?>
                            </td>

                            <td>
                                Rp <?= number_format($row['total']); ?>
                            </td>

                            <td>
                                Rp <?= number_format($row['bayar']); ?>
                            </td>

                            <td>
                                Rp <?= number_format($row['kembalian']); ?>
                            </td>

                            <td>

                                <?= date(
                                    'd M Y H:i',
                                    strtotime($row['created_at'])
                                ); ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include "../partials/footer.php"; ?>