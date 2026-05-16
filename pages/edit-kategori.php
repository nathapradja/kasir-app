<?php include "../partials/header.php"; ?>
<?php include "../config/database.php"; ?>

<?php

/* =========================
   AMBIL ID
========================= */

$id = $_GET['id'];

/* =========================
   AMBIL DATA BERDASARKAN ID
========================= */

$query = "
    SELECT * FROM kategori
    WHERE id = $id
";

$result = pg_query($conn, $query);

$data = pg_fetch_assoc($result);

/* =========================
   UPDATE DATA
========================= */

if(isset($_POST['update'])){

    $nama_kategori = $_POST['nama_kategori'];

    $update = "
        UPDATE kategori
        SET nama_kategori = '$nama_kategori'
        WHERE id = $id
    ";

    pg_query($conn, $update);

    header("Location: kategori.php");
}

?>

<?php include "../partials/sidebar.php"; ?>

<div class="main-content">

    <?php include "../partials/navbar.php"; ?>

    <div class="content">

        <!-- HEADER -->

        <div class="page-header">
            <h1>Edit Kategori</h1>
        </div>

        <!-- FORM -->

        <div class="form-card">

            <form action="" method="POST">

                <div class="form-group">

                    <label>Nama Kategori</label>

                    <input 
                        type="text"
                        name="nama_kategori"
                        value="<?= $data['nama_kategori']; ?>"
                        required
                    >

                </div>

                <button type="submit" name="update">
                    Update Kategori
                </button>

            </form>

        </div>

    </div>

</div>

<?php include "../partials/footer.php"; ?>