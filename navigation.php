<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once "auth.php";

    $auth = new Auth();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light w-100">
    <div class="container m-0 p-0">
        <a href="index.php" class="navbar-brand text-primary">iKarRental</a>

        <?php if ($auth->is_authenticated()): ?>
            <div class="d-flex justify-content-between align-items-center">
                <a href="logout.php" class="text-dark bg-primary font-weight-bold rounded-pill p-2 w-auto inline text-decoration-none mr-3">Kijelentkezés</a>
                <a href="profile.php" class="nav-item">
                    <img src="/assets/profile_pic.jpg" alt="Profilkép" class="profile-pic rounded-circle">
                </a>
            </div>

        <?php else: ?>
            <ul class="navbar-nav ml-auto d-flex flex-row align-items-center">
                <li class="nav-item font-weight-bold">
                    <a href="login.php" class="nav-link text-primary">Bejelenkezés</a>
                </li>
                <li class="nav-item text-dark bg-primary font-weight-bold rounded-pill p-2 ml-2">
                    <a href="register.php" class="nav-link">Regisztráció</a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</nav>