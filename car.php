<?php
require_once 'jsonio.php';
require_once 'jsonstorage.php';

$storage = new JsonStorage('data/cars.json');
$cars = $storage->all();

$car_id = $_GET['id'] ?? null;
$car = null;

foreach ($cars as $c) {
    if ($c['id'] == $car_id) {
        $car = $c;
        break;
    }
}

if (!$car) {
    echo "Car not found.";
    exit;
}

$price = floor($car["daily_price_huf"]/1000) . '.' . ($car["daily_price_huf"]%1000 == 0 ? '000' : $car["daily_price_huf"]%1000);

?>

<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <title>iKarRental - <?php echo $car["brand"] . ' ' . $car["model"] ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="custom.css" rel="stylesheet">
</head>
<body class="bg-dark">
    <?php include 'navigation.php'; ?>


    <main class="d-flex flex-column justify-content-center align-items-center w-75 mx-auto mt-5">

        <h1 class="align-self-end text-primary text-huge"><?php echo $car["brand"]?> <strong><?php echo $car["model"] ?></strong></h1>

        <div class="car-detail-box d-flex flex-row justify-content-between w-100">
            <div class="img-box w-49 overflow-hidden">
                <img src="<?php echo $car["image"] ?>" alt="<?php $car["brand"] . $car["model"]?>" class="w-100 rounded">
            </div>
            <div class="d-flex flex-column justify-content-between">
                <div class="bg-light h-75 text-primary rounded pt-3 px-3 d-flex flex-column justify-content-between">
                    <div class="details-box d-flex flex-column justify-content-between font-weight-bold h-100">
                        <div class="details d-flex flex-row justify-content-between">
                            <div class="left"><p>Üzemanyag: <?php echo $car["fuel_type"] ?></p>
                            <p>Gyártási év: <?php echo $car["year"] ?></p>    
                        </div>
                            <div class="right">
                                <p>Váltó: <?php echo $car["transmission"] ?></p>
                                <p>Férőhelyek száma: <?php echo $car["passengers"] ?></p>
                            </div>
                        </div>
                        <label class="text-primary text-center text-large"><?php echo $price ?>Ft<span class="per-day">/nap</span></label>
                    </div>
                </div>
                <div class="btn-box d-flex flex-row justify-content-around">
                        <button class="date-picker rounded-pill bg-tertiary font-weight-bold border border-dark p-2">Dátum kiválasztása</button>
                        <button class="reserve rounded-pill bg-primary p-2 border border-dark font-weight-bold">Lefoglalom</button>
                    </div>
                </div>
        </div>
    </main>
</body>
</html>