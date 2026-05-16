<?php include "../config/auth.php"; ?>
<?php include "../partials/header.php"; ?>
<?php include "../partials/header.php"; ?>
<?php include "../config/database.php"; ?>

<?php

/* =========================
   TAMBAH DATA
========================= */

if(isset($_POST['submit'])){

    $nama_kategori = $_POST['nama_kategori'];

    $insert = "
        INSERT INTO kategori (nama_kategori)
        VALUES ('$nama_kategori')
    ";

    pg_query($conn, $insert);

    header("Location: kategori.php");
}

/* =========================
   HAPUS DATA
========================= */

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    $delete = "
        DELETE FROM kategori
        WHERE id = $id
    ";

    pg_query($conn, $delete);

    header("Location: kategori.php");
}

/* =========================
   TAMPIL DATA
========================= */

$query = "SELECT * FROM kategori ORDER BY id DESC";

$result = pg_query($conn, $query);

?>

<?php include "../partials/sidebar.php"; ?>

<div class="main-content">

    <?php include "../partials/navbar.php"; ?>

    <div class="content">

        <!-- PAGE HEADER -->

        <div class="page-header">
            <h1>Data Kategori</h1>
        </div>

        <!-- FORM TAMBAH -->

        <div class="form-card">

            <form action="" method="POST">

                <div class="form-group">

                    <label>Nama Kategori</label>

                    <input 
                        type="text"
                        name="nama_kategori"
                        placeholder="Masukkan kategori..."
                        required
                    >

                </div>

                <button type="submit" name="submit">
                    Tambah Kategori
                </button>

            </form>

        </div>

        <!-- TABLE -->

        <div class="table-card">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>

                </thead>

                <tbody>

                    <?php while($row = pg_fetch_assoc($result)) : ?>

                        <tr>

                            <td>
                                <?= $row['id']; ?>
                            </td>

                            <td>
                                <?= $row['nama_kategori']; ?>
                            </td>

                            <td>

                                <!-- EDIT -->

                                <a 
                                    href="edit-kategori.php?id=<?= $row['id']; ?>" 
                                    class="btn-edit"
                                >
                                    Edit
                                </a>

                                <!-- DELETE -->

                                <a 
                                    href="kategori.php?hapus=<?= $row['id']; ?>"
                                    class="btn-delete"
                                    onclick="return confirm('Yakin hapus data?')"
                                >
                                    Hapus
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include "../partials/footer.php"; ?>