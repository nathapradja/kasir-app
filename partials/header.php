<?php

if(!isset($_SESSION['login'])){

    header("Location: ../auth/login.php");

    exit;
}
?>

<?php include "../config/app.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $app_name ?></title>

    <!-- GOOGLE FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    
    <!-- javascript -->
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>