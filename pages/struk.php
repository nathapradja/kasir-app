<?php

include "../config/database.php";

/* =========================
   VALIDASI ID
========================= */

if(
    !isset($_GET['id']) ||
    empty($_GET['id'])
){

    die("ID transaksi tidak ditemukan");

}

$id = $_GET['id'];

/* =========================
   DATA TRANSAKSI
========================= */

$query_transaksi = "
    SELECT *
    FROM transaksi
    WHERE id = '$id'
";

$result_transaksi =
    pg_query($conn, $query_transaksi)
    or die(pg_last_error($conn));

$transaksi =
    pg_fetch_assoc($result_transaksi);

/* =========================
   DETAIL TRANSAKSI
========================= */

$query_detail = "
    SELECT
        detail_transaksi.*,
        barang.nama_barang
    FROM detail_transaksi
    JOIN barang
    ON detail_transaksi.barang_id = barang.id
    WHERE transaksi_id = '$id'
";

$result_detail =
    pg_query($conn, $query_detail)
    or die(pg_last_error($conn));

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Struk</title>

    <style>

        body{
            font-family: monospace;
            background:#f1f5f9;
            padding:40px;
        }

        .receipt{
            width:340px;
            margin:auto;
            background:white;
            padding:24px;
            border-radius:12px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
        }

        .center{
            text-align:center;
        }

        h2{
            margin-bottom:4px;
        }

        .line{
            border-top:1px dashed #999;
            margin:14px 0;
        }

        .item{
            margin-bottom:14px;
        }

        .item-name{
            font-weight:bold;
            margin-bottom:4px;
        }

        .flex{
            display:flex;
            justify-content:space-between;
        }

        .total{
            font-size:18px;
            font-weight:bold;
        }

        .btn-print{
            margin-top:24px;
            width:100%;
            height:44px;
            border:none;
            background:#2563eb;
            color:white;
            border-radius:10px;
            cursor:pointer;
            font-size:16px;
            font-weight:600;
        }

        @media print{

            body{
                background:white;
                padding:0;
            }

            .btn-print{
                display:none;
            }

            .receipt{
                box-shadow:none;
                border:none;
            }

        }

    </style>

</head>

<body>

    <div class="receipt">

        <div class="center">

            <h2>Kasir App</h2>

            <p>
                Struk Pembayaran
            </p>

        </div>

        <div class="line"></div>

        <div class="flex">
            <span>ID</span>
            <span>#<?= $transaksi['id']; ?></span>
        </div>

        <div class="flex">
            <span>Tanggal</span>
            <span>
                <?= date('d-m-Y H:i'); ?>
            </span>
        </div>

        <div class="line"></div>

        <!-- DETAIL ITEM -->

        <?php while($detail =
            pg_fetch_assoc($result_detail)) : ?>

            <div class="item">

                <div class="item-name">

                    <?= $detail['nama_barang']; ?>

                </div>

                <div class="flex">

                    <span>

                        <?= $detail['qty']; ?>
                        x
                        Rp <?= number_format(
                            $detail['subtotal'] /
                            $detail['qty']
                        ); ?>

                    </span>

                    <span>

                        Rp <?= number_format(
                            $detail['subtotal']
                        ); ?>

                    </span>

                </div>

            </div>

        <?php endwhile; ?>

        <div class="line"></div>

        <!-- TOTAL -->

        <div class="flex total">

            <span>Total</span>

            <span>

                Rp <?= number_format(
                    $transaksi['total']
                ); ?>

            </span>

        </div>

        <br>

        <div class="flex">

            <span>Bayar</span>

            <span>

                Rp <?= number_format(
                    $transaksi['bayar']
                ); ?>

            </span>

        </div>

        <div class="flex">

            <span>Kembalian</span>

            <span>

                Rp <?= number_format(
                    $transaksi['kembalian']
                ); ?>

            </span>

        </div>

        <div class="line"></div>

        <div class="center">

            <p>
                Terima kasih!
            </p>

        </div>

        <!-- BUTTON PRINT -->

        <button
            class="btn-print"
            onclick="window.print()"
        >

            Cetak Struk

        </button>

    </div>

</body>
</html>