<?php include "../partials/header.php"; ?>
<?php include "../config/database.php"; ?>

<?php

$id = $_GET['id'];

/* =========================
   AMBIL DATA BARANG
========================= */

$query = "
    SELECT * FROM barang
    WHERE id = $id
";

$result = pg_query($conn, $query);

$data = pg_fetch_assoc($result);

/* =========================
   DATA KATEGORI
========================= */

$kategori = pg_query(
    $conn,
    "SELECT * FROM kategori ORDER BY id DESC"
);

/* =========================
   UPDATE
========================= */

if(isset($_POST['update'])){

    $nama_barang = $_POST['nama_barang'];
    $kategori_id = $_POST['kategori_id'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    /* =========================
       CEK GAMBAR BARU
    ========================= */

    if($_FILES['gambar']['name'] != ''){

        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];

        move_uploaded_file(
            $tmp,
            "../assets/uploads/" . $gambar
        );

    } else {

        $gambar = $data['gambar'];
    }

    /* =========================
       UPDATE QUERY
    ========================= */

    $update = "
        UPDATE barang

        SET
            nama_barang = '$nama_barang',
            kategori_id = '$kategori_id',
            harga = '$harga',
            stok = '$stok',
            gambar = '$gambar'

        WHERE id = $id
    ";

    pg_query($conn, $update);

    header("Location: barang.php?succes=update");
}

?>

<?php include "../partials/sidebar.php"; ?>

<div class="main-content">

    <?php include "../partials/navbar.php"; ?>

    <div class="content">

        <div class="page-header">
            <h1>Edit Barang</h1>
        </div>

        <!-- FORM -->

        <div class="form-card barang-form">

            <form 
                action=""
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- NAMA -->

                <div class="form-group">

                    <label>Nama Barang</label>

                    <input 
                        type="text"
                        name="nama_barang"
                        value="<?= $data['nama_barang']; ?>"
                        required
                    >

                </div>

                <!-- KATEGORI -->

                <div class="form-group">

                    <label>Kategori</label>

                    <select name="kategori_id" required>

                        <?php while($k = pg_fetch_assoc($kategori)) : ?>

                            <option 
                                value="<?= $k['id']; ?>"

                                <?= $k['id'] == $data['kategori_id']
                                    ? 'selected'
                                    : '';
                                ?>
                            >

                                <?= $k['nama_kategori']; ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- HARGA -->

                <div class="form-group">

                    <label>Harga</label>

                    <input 
                        type="number"
                        name="harga"
                        value="<?= $data['harga']; ?>"
                        required
                    >

                </div>

                <!-- STOK -->

                <div class="form-group">

                    <label>Stok</label>

                    <input 
                        type="number"
                        name="stok"
                        value="<?= $data['stok']; ?>"
                        required
                    >

                </div>

                <!-- GAMBAR -->

                <div class="form-group">

                    <label>Gambar Baru</label>

                    <input 
                        type="file"
                        name="gambar"
                    >

                </div>

                <!-- PREVIEW -->

                <div class="form-group">

                    <label>Preview</label>

                    <img 
                        src="../assets/uploads/<?= $data['gambar']; ?>"
                        width="120"
                        style="border-radius:12px;"
                    >

                </div>

                <button type="submit" name="update">
                    Update Barang
                </button>

            </form>

        </div>

    </div>

</div>

<?php include "../partials/footer.php"; ?>