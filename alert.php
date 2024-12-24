<?php
    session_start();

    $status = $_GET['status'] ?? null;
    $brand = $_GET['brand'] ?? null;
    $model = $_GET['model'] ?? null;
    $from = $_GET['from'] ?? null;
    $to = $_GET['to'] ?? null;
    $car_id = $_GET['id'] ?? null;

?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <?php if($status === "success"): ?>
        <title>iKarRental - Sikeres foglalás!</title>
    <?php else: ?>
        <title>iKarRental - Sikertelen foglalás!</title>
    <?php endif; ?>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="custom.css" rel="stylesheet">
</head>
<body style="height: 100dvh;" class="d-flex flex-column justify-content-stretch">
    <?php include 'navigation.php'; ?>
    
    <main class="bg-dark text-primary text-center h-100 d-flex flex-column justify-content-center align-items-center">
            <?php if ($status === "success"): ?>
                <img src="/assets/success.png" alt="Sikeres  foglalást imitáló kép." class="mb-4"/>
            <?php else: ?>
                <img src="/assets/fail.png" alt="Sikertelen foglalást imitáló kép." class="mb-4" />
            <?php endif; ?>

            <h1>
                <?php if ($status === "success"): ?>
                    Sikeres foglalás!
                <?php else: ?>
                    Sikertelen foglalás!
                <?php endif; ?>
            </h1>

            <?php if ($status === 'success'): ?>
                <p class="m-0">A(z) <strong><?php echo $brand ?> <?php echo $model?></strong> autó sikeresen lefoglalva <?php echo $from ?> &mdash; <?php  echo $to ?> intervallumra.</p>
                <p>Foglalásod státuszát a profiloldaladon követheted nyomon.</p>
                <a href="profile.php" class="text-dark bg-primary font-weight-bold rounded-pill px-2 py-1 w-auto inline border border-dark" style="height: max-content;">Profilom</a>
            <?php else: ?>
                <p class="m-0">A(z) <strong><?php echo $brand?> <?php echo $model?></strong> nem elérhető a megadott <?php echo $from?>&mdash;<?php echo $to?> intervallumban.</p>
                <p>Próbálj megadni egy másik intervallumot, vagy keress egy másik járművet.</p>
                <a href="car.php?id=<?= $car_id ?>" class="text-dark bg-primary font-weight-bold rounded-pill px-2 py-1 w-auto inline border border-dark" style="height: max-content;">Vissza a jármű oldalára</a>
            <?php endif; ?>
    </main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>