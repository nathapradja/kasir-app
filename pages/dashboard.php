<?php

include "../config/auth.php";
include "../config/database.php";

/* =========================
   TOTAL BARANG
========================= */

$query_barang = "
    SELECT COUNT(*) as total
    FROM barang
";

$result_barang =
    pg_query($conn, $query_barang);

$total_barang =
    pg_fetch_assoc($result_barang);

/* =========================
   TOTAL TRANSAKSI
========================= */

$query_transaksi = "
    SELECT COUNT(*) as total
    FROM transaksi
";

$result_transaksi =
    pg_query($conn, $query_transaksi);

$total_transaksi =
    pg_fetch_assoc($result_transaksi);

/* =========================
   TOTAL PENDAPATAN
========================= */

$query_pendapatan = "
    SELECT COALESCE(SUM(total),0)
    as total
    FROM transaksi
";

$result_pendapatan =
    pg_query($conn, $query_pendapatan);

$total_pendapatan =
    pg_fetch_assoc($result_pendapatan);

/* =========================
   DATA CHART TRANSAKSI
========================= */

$query_chart = "
    SELECT
        id,
        total
    FROM transaksi
    ORDER BY id ASC
";

$result_chart =
    pg_query($conn, $query_chart);

$label_chart = [];
$data_chart = [];

while($chart =
    pg_fetch_assoc($result_chart)){

    $label_chart[] =
        "TRX ".$chart['id'];

    $data_chart[] =
        $chart['total'];

}

/* =========================
   DATA STOK
========================= */

$query_stok = "
    SELECT
        nama_barang,
        stok
    FROM barang
    ORDER BY id ASC
";

$result_stok =
    pg_query($conn, $query_stok);

$label_stok = [];
$data_stok = [];

while($stok =
    pg_fetch_assoc($result_stok)){

    $label_stok[] =
        $stok['nama_barang'];

    $data_stok[] =
        $stok['stok'];

}

include "../partials/header.php";

?>

<?php include "../partials/sidebar.php"; ?>

<div class="main-content">

    <?php include "../partials/navbar.php"; ?>

    <div class="content">

        <!-- PAGE HEADER -->

        <div class="page-header">

            <h1>
                Dashboard Statistik
            </h1>

        </div>

        <!-- CARD -->

        <div class="dashboard-grid">

            <!-- TOTAL BARANG -->

            <div class="dashboard-card">

                <h3>Total Barang</h3>

                <h1>

                    <?= $total_barang['total']; ?>

                </h1>

            </div>

            <!-- TOTAL TRANSAKSI -->

            <div class="dashboard-card">

                <h3>Total Transaksi</h3>

                <h1>

                    <?= $total_transaksi['total']; ?>

                </h1>

            </div>

            <!-- TOTAL PENDAPATAN -->

            <div class="dashboard-card">

                <h3>Total Pendapatan</h3>

                <h1>

                    Rp
                    <?= number_format(
                        $total_pendapatan['total']
                    ); ?>

                </h1>

            </div>

        </div>

        <!-- CHART TRANSAKSI -->

        <div class="chart-card mt-24">

            <h2>
                Grafik Transaksi
            </h2>

            <canvas id="transaksiChart"></canvas>

        </div>

        <!-- CHART STOK -->

        <div class="chart-card mt-24">

            <h2>
                Grafik Stok Barang
            </h2>

            <canvas id="stokChart"></canvas>

        </div>

    </div>

</div>

<!-- CHART TRANSAKSI -->

<script>

const transaksiChart =
document.getElementById(
    'transaksiChart'
);

Chart.defaults.color = "#cbd5e1";

new Chart(transaksiChart, {

    type: 'line',

    data: {

        labels:
        <?= json_encode($label_chart); ?>,

        datasets: [{

            label: 'Total Transaksi',

            data:
            <?= json_encode($data_chart); ?>,

            borderWidth: 3,
            tension: 0.3

        }]

    }

});

</script>

<!-- CHART STOK -->

<script>

const stokChart =
document.getElementById(
    'stokChart'
);


new Chart(stokChart, {

    type: 'bar',

    data: {

        labels:
        <?= json_encode($label_stok); ?>,

        datasets: [{

            label: 'Stok Barang',

            data:
            <?= json_encode($data_stok); ?>,

            borderWidth: 1

        }]

    }

});

</script>

<?php include "../partials/footer.php"; ?>