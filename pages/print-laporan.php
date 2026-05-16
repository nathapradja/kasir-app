<?php

include "../config/database.php";

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

$query = "
    SELECT *
    FROM transaksi
    $where
    ORDER BY id DESC
";

$result = pg_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>Print Laporan</title>

    <style>

        body{
            font-family:Arial;
            padding:40px;
        }

        h1{
            margin-bottom:30px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th,
        table td{
            border:1px solid #000;
            padding:12px;
            text-align:left;
        }

    </style>

</head>
<body onload="window.print()">

    <h1>Laporan Penjualan</h1>

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Tanggal</th>

            </tr>

        </thead>

        <tbody>

            <?php while($row = pg_fetch_assoc($result)) : ?>

                <tr>

                    <td><?= $row['id']; ?></td>

                    <td><?= $row['nama_pelanggan']; ?></td>

                    <td>
                        Rp <?= number_format($row['total_harga']); ?>
                    </td>

                    <td>
                        <?= date(
                            'd M Y',
                            strtotime($row['created_at'])
                        ); ?>
                    </td>

                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

</body>
</html>