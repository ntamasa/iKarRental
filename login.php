<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "auth.php";

    $auth = new Auth();
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $error = 'Kérjük, töltse ki mindkét mezőt!';
            } else {
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';

                $user = $auth->check_credentials($email, $password);
                if ($user) {
                    $auth->login($user);
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Hibás e-mail cím vagy jelszó!";
                }
            }
        }
        
    }
?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <title>iKarRental - Bejelentkezés</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="custom.css" rel="stylesheet">
</head>
<body class="bg-dark" style="height: 100dvh;">
    <?php include "navigation.php"; ?>

    <main class="d-flex flex-column justify-content-between align-items-center my-5 h-50">
        <h1 class="text-primary text-huge m-0">Belépés</h1>
        <form method="post" novalidate class="w-50 d-flex flex-column">
            <div>
                <label for="email" class="text-primary block mb-0">E-mail cím</label>
                <input type="email" name="email" id="email" class="rounded w-100 border border-dark px-3 py-2 mb-3" placeholder="jakab.gipsz@ikarrental.net" >
            </div>

            <div>
                <label for="password" class="text-primary block mb-0">Jelszó</label>
                <input type="password" name="password" id="password" class="rounded w-100 border border-dark px-3 py-2 mb-3" placeholder="********" >
            </div>

            <button type="submit" class="text-dark align-self-end bg-primary font-weight-bold rounded-pill py-2 px-3 w-auto border border-dark">Belépés</button>
            </form>

            <?php if ($error): ?>
                <div class="alert alert-danger mt-5" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>