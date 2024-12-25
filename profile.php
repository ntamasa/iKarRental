<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once "auth.php";
    
    $auth = new Auth();
    if (!$auth->is_authenticated()) {
        header("Location: login.php");
        exit();
    }
    
    require_once 'jsonio.php';
    require_once 'jsonstorage.php';
    
    $storage = new JsonStorage('data/cars.json');
    $res_storage = new JsonStorage('data/reservations.json');

    $reservations = $res_storage->all();
    $cars = $storage->all();

    $user_email = $_SESSION['user']['email'] ?? null;
    $full_name = $_SESSION['user']['full_name'] ?? null;

    $user_reservations = array_filter($reservations, function($reservation) use ($user_email) {
        return $reservation['user_email'] == $user_email;
    });

    // get cars that have been reserved by the user

    $filtered_reservations = [];
    foreach ($user_reservations as $reservation) {
        $car = array_filter($cars, function($car) use ($reservation) {
            return $car['id'] == $reservation['car_id'];
        });
        $date_arr_from = explode('-', $reservation['from']);
        $date_arr_to = explode('-', $reservation['to']);

        $filtered_reservations[] = [
            'car' => array_values($car)[0],
            'from' => implode('.', array_slice($date_arr_from, 1, 2)),
            'to' => implode('.', array_slice($date_arr_to, 1, 2))
        ];
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <title>iKarRental - Profil</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="custom.css" rel="stylesheet">
</head>
<body class="bg-dark">
    <?php include 'navigation.php' ?>    

    <header class="text-primary d-flex align-items-center h-25" style="padding: 5rem 12.5rem;" >
        <img src="/assets/profile_pic.jpg" alt="Profilkép"  class="rounded" style="height: 20rem;"/>
        <div class="d-flex flex-column align-items-start justify-content-center pl-5">
            <p class="m-0">Bejelentkezve, mint</p>
            <h2 class="m-0 text-large"><?php echo $full_name ?></h2>
        </div>
    </header>

    <main class="" style="padding: 5rem 12.5rem; padding-top: 0;">
        <h2 class="text-primary mb-4">Fogalalásaim</h2>

        <div class="row">
            <?php if(count($filtered_reservations) > 0): ?>
                <?php foreach ($filtered_reservations as $reservation): ?>
                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-4">
                        <?php include 'card.php'; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <h2 class="text-center text-primary">Még nem bérelt nálunk autót! 😔</h2>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <a href="logout.php">Kijelentkezés</a>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>