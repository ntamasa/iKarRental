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

    $user = $_SESSION['user'];

    $role = $_SESSION['user']['role'] ?? '';

    $user_email = $_SESSION['user']['email'] ?? null;
    $full_name = $_SESSION['user']['full_name'] ?? null;

    $filtered_reservations = [];

    if ($role === "user") {
        $user_reservations = array_filter($reservations, function($reservation) use ($user_email) {
            return $reservation['user_email'] == $user_email;
        });
    } else {
        $user_reservations = $reservations;
    }
    
    // get cars that have been reserved by the user
    foreach ($user_reservations as $reservation) {
        $car = array_filter($cars, function($car) use ($reservation) {
            return $car['_id'] == $reservation['car_id'];
        });
        $date_arr_from = explode('-', $reservation['from']);
        $date_arr_to = explode('-', $reservation['to']);

        $filtered_reservations[] = [
            "_id" => $reservation['_id'],
            'car' => array_values($car)[0],
            'user_email' => $reservation['user_email'],
            'from' => implode('.', array_slice($date_arr_from, 1, 2)),
            'to' => implode('.', array_slice($date_arr_to, 1, 2))
        ];
    }

    $error = '';
    // add new car validation
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $brand = $_POST['brand'] ?? '';
        $model = $_POST['model'] ?? '';
        $year = $_POST['year'] ?? '';
        $fuel_type = $_POST['fuel_type'] ?? '';
        $image = $_POST['image'] ?? '';
        $transmission = $_POST['transmission'] ?? '';
        $passengers = $_POST['passengers'] ?? '';
        $price = $_POST['price'] ?? '';

        if (empty($brand) || empty($model) || empty($year) || empty($fuel_type) || $fuel_type === '-' || empty($image) || empty($transmission) || $transmission === "-" || empty($passengers) || empty($price)) {
            $error = 'Kérjük, töltse ki az összes mezőt!' . $fuel_type . $transmission;
        } elseif (!is_numeric($year) || !is_numeric($passengers) || !is_numeric($price)) {
            $error = 'A gyártási év, férőhelyek száma és az ár mezők csak számokat tartalmazhatnak!';
        } elseif ($transmission !== 'Manual' && $transmission !== 'Automatic') {
            $error = 'A váltó csak "Manuális" vagy "Automata" lehet!';
        } elseif ($fuel_type !== 'Petrol' && $fuel_type !== 'Electric') {
            $error = 'Az üzemanyag típusa csak "Petrol" vagy "Electric" lehet!';
        } else {
            $new_car = [
                'brand' => $brand,
                'model' => $model,
                'year' => intval($year),
                'fuel_type' => $fuel_type,
                'image' => $image,
                'transmission' => $transmission,
                'passengers' => intval($passengers),
                'daily_price_huf' => intval($price)
            ];
            $storage->insert((object) $new_car);
            header('Location: index.php');
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete']) && isset($_POST['res_id'])) {
            $res_storage->delete($_POST['res_id']);
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        }
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
        <?php if ($role === 'admin'): ?>
            <section class="mb-5">
                <form method="post" novalidate>
                    <h2 class="text-primary mb-4">Új autó hozzáadása 🚗</h2>

                    <div class="row">
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="brand" class="text-primary block mb-0">Márka</label>
                            <input type="text" name="brand" class="form-control mb-3" placeholder="Márka" value="<?php echo $brand ?? '' ?>">
                        </div>

                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="model" class="text-primary block mb-0">Modell</label>
                            <input type="text" name="model" class="form-control mb-3" placeholder="Modell" value="<?php echo $model ?? '' ?>" >
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="year" class="text-primary block mb-0">Gyártási év</label>
                            <input type="number" name="year" class="form-control mb-3" placeholder="Év" 
                            value="<?php echo $year ?? '' ?>">
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="image" class="text-primary block mb-0">Kép URL</label>
                            <input type="text" name="image" class="form-control mb-3" placeholder="Kép URL" value="<?php echo $image ?? '' ?>">
                        </div>
                        
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="passengers" class="text-primary block mb-0">Férőhelyek száma</label>
                            <input type="number" name="passengers" class="form-control mb-3" placeholder="Férőhelyek száma" value="<?php echo $passengers ?? '' ?>">
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            <label for="price" class="text-primary block mb-0">Ár</label>
                            <input type="number" name="price" class="form-control mb-3" placeholder="Ár" value="<?php echo $price ?? '' ?>">
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 my-3">
                        <select name="fuel_type" id="fuel_type" class="p-2 w-100 rounded">
                                <option value="-">Üzemanyag típusa</option>
                                <option value="Petrol" <?php echo (isset($_POST['fuel_type']) && $_POST['fuel_type'] === 'Petrol') ? 'selected' : ''; ?>>Petrol</option>
                                <option value="Electric" <?php echo (isset($_POST['fuel_type']) && $_POST['fuel_type'] === 'Electric') ? 'selected' : ''; ?>>Electric</option>
                            </select>
                        </div>
                        <div class="col col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 align-self-center my-3">
                            <select name="transmission" id="transmission" class="p-2 w-100 rounded">
                                <option value="-">Váltó típusa</option>
                                <option value="Manual" <?php echo (isset($_POST['transmission']) && $_POST['transmission'] === 'manual') ? 'selected' : ''; ?>>Manuális</option>
                                <option value="Automatic" <?php echo (isset($_POST['transmission']) && $_POST['transmission'] === 'automatic') ? 'selected' : ''; ?>>Automata</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <button type="submit" class="rounded-pill bg-primary p-2 border border-dark font-weight-bold ">Létrehozás</button>
                        <?php if ($error): ?>
                            <div class="alert alert-danger m-0" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>

                
            </section>
        <?php endif; ?>

        <section>
            <h2 class="text-primary mb-4">
                <?php echo $role === 'user' ? 'Foglalásaim' : 'Összes foglalás' ?>
            </h2>

            <div class="row">
                <?php if(count($filtered_reservations) > 0): ?>
                    <?php foreach ($filtered_reservations as $reservation): ?>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-3 mb-4">
                            <?php include 'card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <h2 class="text-center text-primary">Még nem bérelt nálunk autót! 😔</h2>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>