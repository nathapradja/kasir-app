<?php include "../config/auth.php"; ?>
<?php include "../partials/header.php"; ?>

<?php include "../partials/header.php"; ?>
<?php include "../config/database.php"; ?>

<?php include "../helpers/rupiah.php"; ?>
<?php include "../helpers/redirect.php"; ?>
<?php include "../helpers/upload.php"; ?>

<?php

/* ========================================
   VALIDATION ERROR
======================================== */

$error = [];

/* ========================================
   PAGINATION
======================================== */

$limit = 5;

$page = isset($_GET['page'])
    ? $_GET['page']
    : 1;

$offset = ($page - 1) * $limit;

/* ========================================
   SEARCH
======================================== */

$search = isset($_GET['search'])
    ? $_GET['search']
    : '';

/* ========================================
   TOTAL DATA
======================================== */

$total_query = "
    SELECT COUNT(*) AS total
    FROM barang
    WHERE nama_barang ILIKE '%$search%'
";

$total_result = pg_query($conn, $total_query);

$total_data = pg_fetch_assoc($total_result);

$total_rows = $total_data['total'];

$total_pages = ceil($total_rows / $limit);

/* ========================================
   TAMBAH BARANG + VALIDASI
======================================== */

if(isset($_POST['submit'])){

    $nama_barang = trim($_POST['nama_barang']);
    $kategori_id = $_POST['kategori_id'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    /* ========================================
       VALIDASI INPUT
    ======================================== */

    if($nama_barang == ''){

        $error[] = "Nama barang wajib diisi";
    }

    if($harga <= 0){

        $error[] = "Harga harus lebih dari 0";
    }

    if($stok < 0){

        $error[] = "Stok tidak boleh negatif";
    }

    /* ========================================
       VALIDASI GAMBAR
    ======================================== */

    $gambar_file = $_FILES['gambar'];

    $gambar = $gambar_file['name'];

    $size = $gambar_file['size'];

    $format = strtolower(
        pathinfo($gambar, PATHINFO_EXTENSION)
    );

    $allowed = ['jpg', 'jpeg', 'png'];

    if(!in_array($format, $allowed)){

        $error[] = "Format gambar harus JPG PNG JPEG";
    }

    if($size > 2000000){

        $error[] = "Ukuran gambar maksimal 2MB";
    }

    /* ========================================
       JIKA VALIDASI LOLOS
    ======================================== */

    if(empty($error)){

        /* UPLOAD HELPER */

        $gambar = upload($_FILES['gambar']);

        /* INSERT */

        $insert = "
            INSERT INTO barang (
                kategori_id,
                nama_barang,
                harga,
                stok,
                gambar
            )

            VALUES (
                '$kategori_id',
                '$nama_barang',
                '$harga',
                '$stok',
                '$gambar'
            )
        ";

        pg_query($conn, $insert);

        redirect("barang.php?success=tambah");
    }
}

/* ========================================
   DATA BARANG
======================================== */

$query = "
    SELECT
        barang.*,
        kategori.nama_kategori

    FROM barang

    JOIN kategori
    ON barang.kategori_id = kategori.id

    WHERE
        barang.nama_barang ILIKE '%$search%'

    ORDER BY barang.id DESC

    LIMIT $limit
    OFFSET $offset
";

$result = pg_query($conn, $query);

/* ========================================
   DATA KATEGORI
======================================== */

$kategori = pg_query(
    $conn,
    "SELECT * FROM kategori ORDER BY id DESC"
);

?>

<?php include "../partials/sidebar.php"; ?>

<div class="main-content">

    <?php include "../partials/navbar.php"; ?>

    <div class="content">

        <!-- HEADER -->

        <div class="page-header">
            <h1>Data Barang</h1>
        </div>

        <!-- SUCCESS ALERT -->

        <?php if(isset($_GET['success'])) : ?>

            <div class="alert-success">

                <?php if($_GET['success'] == 'tambah') : ?>

                    Data barang berhasil ditambahkan

                <?php elseif($_GET['success'] == 'hapus') : ?>

                    Data barang berhasil dihapus

                <?php elseif($_GET['success'] == 'update') : ?>

                    Data barang berhasil diupdate

                <?php endif; ?>

            </div>

        <?php endif; ?>

        <!-- SEARCH -->

        <div class="search-box">

            <form action="" method="GET">

                <input 
                    type="text"
                    name="search"
                    placeholder="Cari barang..."
                    value="<?= $search; ?>"
                >

                <button type="submit">
                    Search
                </button>

            </form>

        </div>

        <!-- ERROR MESSAGE -->

        <?php if(!empty($error)) : ?>

            <div class="alert-error">

                <ul>

                    <?php foreach($error as $err) : ?>

                        <li><?= $err; ?></li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>

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
                        placeholder="Masukkan nama barang"
                        required
                    >

                </div>

                <!-- KATEGORI -->

                <div class="form-group">

                    <label>Kategori</label>

                    <select name="kategori_id" required>

                        <option value="">
                            -- Pilih Kategori --
                        </option>

                        <?php while($k = pg_fetch_assoc($kategori)) : ?>

                            <option value="<?= $k['id']; ?>">

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
                        placeholder="Masukkan harga"
                        required
                    >

                </div>

                <!-- STOK -->

                <div class="form-group">

                    <label>Stok</label>

                    <input 
                        type="number"
                        name="stok"
                        placeholder="Masukkan stok"
                        required
                    >

                </div>

                <!-- GAMBAR -->

                <div class="form-group">

                    <label>Gambar</label>

                    <input 
                        type="file"
                        name="gambar"
                        required
                    >

                </div>

                <!-- BUTTON -->

                <button type="submit" name="submit">
                    Tambah Barang
                </button>

            </form>

        </div>

        <!-- TABLE -->

        <div class="table-card">

            <table>

                <thead>

                    <tr>

                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if(pg_num_rows($result) > 0) : ?>

                        <?php while($row = pg_fetch_assoc($result)) : ?>

                            <tr>

                                <!-- GAMBAR -->

                                <td>

                                    <img 
                                        src="../assets/uploads/<?= $row['gambar']; ?>"
                                        width="70"
                                    >

                                </td>

                                <!-- NAMA -->

                                <td>
                                    <?= $row['nama_barang']; ?>
                                </td>

                                <!-- KATEGORI -->

                                <td>
                                    <?= $row['nama_kategori']; ?>
                                </td>

                                <!-- HARGA -->

                                <td>
                                    <?= rupiah($row['harga']); ?>
                                </td>

                                <!-- STOK -->

                                <td>

                                    <?php if($row['stok'] <= 5) : ?>

                                        <span class="badge-stock danger">

                                            <?= $row['stok']; ?>

                                        </span>

                                    <?php else : ?>

                                        <span class="badge-stock success">

                                            <?= $row['stok']; ?>

                                        </span>

                                    <?php endif; ?>

                                </td>

                                <!-- AKSI -->

                                <td class="action-buttons">

                                    <!-- EDIT -->

                                    <a 
                                        href="edit-barang.php?id=<?= $row['id']; ?>"
                                        class="btn-edit"
                                    >
                                        Edit
                                    </a>

                                    <!-- DELETE -->

                                    <a 
                                        href="hapus-barang.php?id=<?= $row['id']; ?>"
                                        class="btn-delete"
                                        onclick="return confirm('Hapus barang ini?')"
                                    >
                                        Hapus
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else : ?>

                        <tr>

                            <td colspan="6">

                                <div class="empty-state">

                                    <h3>Data tidak ditemukan</h3>

                                    <p>
                                        Coba kata kunci lain
                                    </p>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

            <!-- PAGINATION -->

            <div class="pagination">

                <?php for($i = 1; $i <= $total_pages; $i++) : ?>

                    <a 
                        href="?page=<?= $i; ?>&search=<?= $search; ?>"

                        class="
                            <?= $page == $i ? 'active-page' : ''; ?>
                        "
                    >
                        <?= $i; ?>
                    </a>

                <?php endfor; ?>

            </div>

        </div>

    </div>

</div>

<?php include "../partials/footer.php"; ?>