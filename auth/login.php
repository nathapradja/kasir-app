<?php

session_start();

include "../config/database.php";

// kalau sudah login
if(isset($_SESSION['login'])){

    header("Location: ../pages/dashboard.php");
    exit;

}

$error = false;

// proses login
if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "
        SELECT *
        FROM users
        WHERE email='$email'
        AND password='$password'
    ";

    $result = pg_query($conn, $query);

    if(pg_num_rows($result) > 0){

        $user = pg_fetch_assoc($result);

        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: ../pages/dashboard.php");
        exit;

    } else {

        $error = true;

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Kasir App</title>

    <!-- GOOGLE FONT -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->

    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">

</head>

<body class="login-body">

    <div class="login-container">

        <div class="login-card">

            <h1>Kasir App</h1>

            <p>Login Admin</p>

            <?php if($error) : ?>

                <div class="alert-error">
                    Email atau Password salah!
                </div>

            <?php endif; ?>

            <form method="POST">

                <div class="login-group">

                    <label>Email</label>

                    <input 
                        type="email"
                        name="email"
                        placeholder="Masukkan email"
                        required
                    >

                </div>

                <div class="login-group">

                    <label>Password</label>

                    <input 
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >

                </div>

                <button type="submit" name="login">
                    Login
                </button>

            </form>

        </div>

    </div>

</body>
</html>