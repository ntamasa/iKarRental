<?php
session_start();
require_once "auth.php";

$auth = new Auth();
if (!$auth->is_authenticated() || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

require_once 'jsonio.php';
require_once 'jsonstorage.php';

$storage = new JsonStorage('data/cars.json');
$cars = $storage->all();

$user_email = $_SESSION['user']['email'] ?? null;

$car_id = $_GET['id'] ?? null;
$car = null;

foreach ($cars as $c) {
    if ($c['_id'] == $car_id) {
        $car = $c;
        break;
    }
}

if (!$car) {
    echo "Car not found.";
    exit;
}

$reservation_storage = new JsonStorage('data/reservations.json');
$reservations = $reservation_storage->all();
$filtered_reservations = array_filter($reservations, function($reservation) use ($car) {
    return $reservation['car_id'] == $car['_id'];
});

// validation for modifying a car
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['brand']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['image']) && isset($_POST['passengers']) && isset($_POST['price']) && isset($_POST['fuel_type'])){
        if (empty($_POST['brand']) || empty($_POST['model']) || empty($_POST['year']) || empty($_POST['image']) || empty($_POST['passengers']) || empty($_POST['price']) || empty($_POST['fuel_type'])) {
            $error = 'Kérjük, töltsön ki minden mezőt!';
        } else {
            $storage->update($car["_id"], [
                'brand' => $_POST['brand'],
                'model' => $_POST['model'],
                'year' => $_POST['year'],
                'image' => $_POST['image'],
                'passengers' => $_POST['passengers'],
                'daily_price_huf' => $_POST['price'],
                'fuel_type' => $_POST['fuel_type'],
                'transmission' => $_POST['transmission']
            ]);
            header("Location: index.php");
            exit;
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
    <title>iKarRental - <?php echo $car["brand"] . ' ' . $car["model"] ?> Módosítás</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="custom.css" rel="stylesheet">
</head>
<body class="bg-dark">
    <?php include 'navigation.php'; ?>


    <main class="d-flex flex-column justify-content-center align-items-center w-75 mx-auto mt-5">

    <section class="mb-5">
                <form method="post" novalidate>
                    <h2 class="text-primary mb-4">
                            <strong><?php echo $car["brand"] . " " . $car["model"] ?></strong> autó módosítása
                    </h2>

                    <div class="row">
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="brand" class="text-primary block mb-0">Márka</label>
                            <input type="text" name="brand" class="form-control mb-3" placeholder="Márka" value="<?php echo $_POST["brand"] ?? $car["brand"] ?? '' ?>">
                        </div>

                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="model" class="text-primary block mb-0">Modell</label>
                            <input type="text" name="model" class="form-control mb-3" placeholder="Modell" value="<?php echo $_POST['model'] ?? $car["model"] ?? '' ?>" >
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="year" class="text-primary block mb-0">Gyártási év</label>
                            <input type="number" name="year" class="form-control mb-3" placeholder="Év" 
                            value="<?php echo $_POST['year'] ?? $car["year"] ?? '' ?>">
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="image" class="text-primary block mb-0">Kép URL</label>
                            <input type="text" name="image" class="form-control mb-3" placeholder="Kép URL" value="<?php echo $_POST['image'] ?? $car["image"] ?? '' ?>">
                        </div>
                        
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="passengers" class="text-primary block mb-0">Férőhelyek száma</label>
                            <input type="number" name="passengers" class="form-control mb-3" placeholder="Férőhelyek száma" value="<?php echo $_POST["passengers"] ?? $car["passengers"] ?? '' ?>">
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="price" class="text-primary block mb-0">Ár</label>
                            <input type="number" name="price" class="form-control mb-3" placeholder="Ár" value="<?php echo $_POST["price"] ?? $car["daily_price_huf"] ?? '' ?>">
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-3">
                        <select name="fuel_type" id="fuel_type" class="p-2 w-100 rounded">
                                <option value="Petrol" <?php echo (($_POST['fuel_type'] ?? $car['fuel_type']) === "Petrol") ? 'selected' : ''; ?>>Petrol</option>
                                <option value="Electric" <?php echo (($_POST['fuel_type'] ??$car['fuel_type']) === 'Electric') ? 'selected' : ''; ?>>Electric</option>
                            </select>
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 align-self-center my-3">
                            <select name="transmission" id="transmission" class="p-2 w-100 rounded">
                                <option value="Manual" <?php echo (($_POST['transmission'] ?? $car['transmission']) === 'manual') ? 'selected' : ''; ?>>Manuális</option>
                                <option value="Automatic" <?php echo (($_POST['transmission'] ?? $car['transmission']) === 'automatic') ? 'selected' : ''; ?>>Automata</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <button type="submit" class="rounded-pill bg-primary p-2 border border-dark font-weight-bold ">Módostás</button>
                        <?php if ($error): ?>
                            <div class="alert alert-danger m-0" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </section>
            
    </main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>