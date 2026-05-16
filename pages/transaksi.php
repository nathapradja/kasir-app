<?php

include "../config/auth.php";
include "../config/database.php";

/* =========================
   SESSION CART
========================= */

if(!isset($_SESSION['cart'])){

    $_SESSION['cart'] = [];

}

/* =========================
   ADD TO CART
========================= */

if(isset($_POST['tambah_cart'])){

    $barang_id = $_POST['barang_id'];
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $qty = $_POST['qty'];

    /* =========================
       VALIDASI QTY
    ========================= */

    if($qty <= 0){

        echo "
        <script>

            alert('Qty tidak valid!');

            window.location='transaksi.php';

        </script>
        ";

        exit;

    }

    /* =========================
       CEK STOK
    ========================= */

    $query_stok = "
        SELECT stok
        FROM barang
        WHERE id = '$barang_id'
    ";

    $result_stok =
        pg_query($conn, $query_stok);

    $data_stok =
        pg_fetch_assoc($result_stok);

    $stok_sekarang =
        $data_stok['stok'];

    /* =========================
       VALIDASI STOK
    ========================= */

    if($qty > $stok_sekarang){

        echo "
        <script>

            alert('Stok tidak cukup!');

            window.location='transaksi.php';

        </script>
        ";

        exit;

    }

    $subtotal = $harga * $qty;

    $_SESSION['cart'][] = [

        'barang_id' => $barang_id,
        'nama' => $nama,
        'harga' => $harga,
        'qty' => $qty,
        'subtotal' => $subtotal

    ];

    header("Location: transaksi.php");
    exit;

}

/* =========================
   HAPUS CART
========================= */

if(isset($_GET['hapus'])){

    $index = $_GET['hapus'];

    unset($_SESSION['cart'][$index]);

    $_SESSION['cart'] =
        array_values($_SESSION['cart']);

    header("Location: transaksi.php");
    exit;

}

/* =========================
   TOTAL
========================= */

$total = 0;

foreach($_SESSION['cart'] as $item){

    $total += $item['subtotal'];

}

/* =========================
   SIMPAN TRANSAKSI
========================= */

if(isset($_POST['simpan'])){

    /* =========================
       VALIDASI CART
    ========================= */

    if(count($_SESSION['cart']) <= 0){

        echo "
        <script>

            alert('Cart masih kosong!');

            window.location='transaksi.php';

        </script>
        ";

        exit;

    }

    $bayar = $_POST['bayar'];

    /* =========================
       VALIDASI BAYAR
    ========================= */

    if($bayar < $total){

        echo "
        <script>

            alert('Uang bayar kurang!');

            window.location='transaksi.php';

        </script>
        ";

        exit;

    }

    $kembalian = $bayar - $total;

    /* =========================
       INSERT TRANSAKSI
    ========================= */

    $insert_transaksi = "
        INSERT INTO transaksi (
            total,
            bayar,
            kembalian
        )
        VALUES (
            '$total',
            '$bayar',
            '$kembalian'
        )
        RETURNING id
    ";

    $result_transaksi =
        pg_query($conn, $insert_transaksi)
        or die(pg_last_error($conn));

    $data_transaksi =
        pg_fetch_assoc($result_transaksi);

    $transaksi_id =
        $data_transaksi['id'];

    /* =========================
       INSERT DETAIL
    ========================= */

    foreach($_SESSION['cart'] as $item){

        $barang_id = $item['barang_id'];
        $qty = $item['qty'];
        $subtotal = $item['subtotal'];

        $insert_detail = "
            INSERT INTO detail_transaksi (

                transaksi_id,
                barang_id,
                qty,
                subtotal

            )
            VALUES (

                '$transaksi_id',
                '$barang_id',
                '$qty',
                '$subtotal'

            )
        ";

        pg_query($conn, $insert_detail)
        or die(pg_last_error($conn));

        /* =========================
           UPDATE STOK
        ========================= */

        $update_stok = "
            UPDATE barang
            SET stok = stok - '$qty'
            WHERE id = '$barang_id'
        ";

        pg_query($conn, $update_stok)
        or die(pg_last_error($conn));

    }

    /* =========================
       RESET CART
    ========================= */

    $_SESSION['cart'] = [];

    /* =========================
       REDIRECT STRUK
    ========================= */

    header("Location: struk.php?id=".$transaksi_id);
    exit;

}

/* =========================
   DATA BARANG
========================= */

$query_barang = "
    SELECT *
    FROM barang
    ORDER BY id DESC
";

$result_barang = pg_query($conn, $query_barang);

/* =========================
   HEADER HTML
========================= */

include "../partials/header.php";

?>

<?php include "../partials/sidebar.php"; ?>

<div class="main-content">

    <?php include "../partials/navbar.php"; ?>

    <div class="content">

        <!-- PAGE HEADER -->

        <div class="page-header">

            <h1>Transaksi Kasir</h1>

        </div>

        <!-- FORM TAMBAH CART -->

        <div class="form-card transaksi-card">

            <form method="POST">

                <!-- BARANG -->

                <div class="form-group">

                    <label>Barang</label>

                    <select
                        name="nama_barang"
                        id="barangSelect"
                        required
                    >

                        <?php
                        while($barang =
                            pg_fetch_assoc($result_barang)) :
                        ?>

                            <option
                                value="<?= $barang['nama_barang']; ?>"
                                data-id="<?= $barang['id']; ?>"
                                data-harga="<?= $barang['harga']; ?>"
                            >

                                <?= $barang['nama_barang']; ?>
                                -
                                Rp <?= number_format($barang['harga']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                    <!-- HIDDEN ID -->

                    <input
                        type="hidden"
                        name="barang_id"
                        id="barang_id"
                    >

                </div>

                <!-- HARGA -->

                <div class="form-group">

                    <label>Harga</label>

                    <input
                        type="number"
                        name="harga"
                        id="harga"
                        readonly
                    >

                </div>

                <!-- QTY -->

                <div class="form-group">

                    <label>Qty</label>

                    <input
                        type="number"
                        name="qty"
                        value="1"
                        min="1"
                        required
                    >

                </div>

                <!-- BUTTON -->

                <button
                    type="submit"
                    name="tambah_cart"
                >

                    Tambah Cart

                </button>

            </form>

        </div>

        <!-- TABLE CART -->

        <div class="table-card mt-24">

            <table>

                <thead>

                    <tr>

                        <th>Barang</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if(count($_SESSION['cart']) > 0) : ?>

                        <?php
                        foreach($_SESSION['cart']
                        as $index => $item) :
                        ?>

                            <tr>

                                <td>
                                    <?= $item['nama'] ?? '-'; ?>
                                </td>

                                <td>
                                    Rp <?= number_format($item['harga']); ?>
                                </td>

                                <td>
                                    <?= $item['qty']; ?>
                                </td>

                                <td>
                                    Rp <?= number_format($item['subtotal']); ?>
                                </td>

                                <td>

                                    <a
                                        href="?hapus=<?= $index; ?>"
                                        class="btn-delete"
                                        onclick="
                                        return confirm(
                                        'Hapus item ini?'
                                        )
                                        "
                                    >

                                        Hapus

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <tr>

                            <td colspan="5">

                                Cart masih kosong

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <!-- TOTAL -->

        <div class="total-box mt-24">

            <h2>

                Total:
                Rp <?= number_format($total); ?>

            </h2>

        </div>

        <!-- FORM BAYAR -->

        <div class="form-card mt-24">

            <form method="POST">

                <div class="form-group">

                    <label>Bayar</label>

                    <input
                        type="number"
                        name="bayar"
                        id="bayarInput"
                        placeholder="Masukkan uang bayar"
                        required
                    >

                </div>

                <!-- KEMBALIAN -->

                <div class="kembalian-box">

                    <h3>

                        Kembalian:
                        <span id="kembalianText">

                            Rp 0

                        </span>

                    </h3>

                </div>

                <button
                    type="submit"
                    name="simpan"

                    <?= count($_SESSION['cart']) <= 0
                    ? 'disabled'
                    : ''; ?>

                >

                    Simpan Transaksi

                </button>

            </form>

        </div>

    </div>

</div>

<!-- AUTO HARGA -->

<script>

const selectBarang =
document.getElementById('barangSelect');

const hargaInput =
document.getElementById('harga');

const barangIdInput =
document.getElementById('barang_id');

function updateHarga(){

    const selected =
    selectBarang.options[
        selectBarang.selectedIndex
    ];

    hargaInput.value =
    selected.dataset.harga;

    barangIdInput.value =
    selected.dataset.id;

}

updateHarga();

selectBarang.addEventListener(
    'change',
    updateHarga
);

</script>

<!-- KEMBALIAN REALTIME -->

<script>

const bayarInput =
document.getElementById('bayarInput');

const kembalianText =
document.getElementById('kembalianText');

const total =
<?= $total; ?>;

bayarInput.addEventListener('input', function(){

    const bayar =
    parseInt(this.value) || 0;

    const kembalian =
    bayar - total;

    kembalianText.innerText =
    'Rp ' +
    kembalian.toLocaleString('id-ID');

});

</script>

<?php include "../partials/footer.php"; ?>