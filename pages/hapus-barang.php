<?php include "../config/database.php"; ?>

<?php

$id = $_GET['id'];

/* =========================
   AMBIL DATA GAMBAR
========================= */

$query = "
    SELECT * FROM barang
    WHERE id = $id
";

$result = pg_query($conn, $query);

$data = pg_fetch_assoc($result);

/* =========================
   HAPUS FILE GAMBAR
========================= */

$file = "../assets/uploads/" . $data['gambar'];

if(file_exists($file)){

    unlink($file);
}

/* =========================
   HAPUS DATABASE
========================= */

$delete = "
    DELETE FROM barang
    WHERE id = $id
";

pg_query($conn, $delete);

header("Location: barang.php?succes=hapus");