<?php
session_start();
require_once "auth.php";

$auth = new Auth();
if (!$auth->is_authenticated()) {
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


$reservation_storage = new JsonStorage('data/reservations.json');
$reservations = $reservation_storage->all();
$filtered_reservations = array_filter($reservations, function($reservation) use ($car) {
    return $reservation['car_id'] == $car['id'];
});

// validation of the date picking modal
$error = '';
$startDate = $_SESSION['startDate'] ?? '';
$endDate = $_SESSION['endDate'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'save_dates') {
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';

        if (empty($startDate) || empty($endDate)) {
            $error = 'Kérjük, töltse ki mindkét dátumot.';
        } elseif (strtotime($startDate) > strtotime($endDate)) {
            $error = 'A kezdő dátum nem lehet későbbi, mint a befejező dátum.';
        } elseif (date('Y-m-d') > $startDate) {
            $error = 'A kezdő dátum legkorábban a mai nap.';  
        } else {
            // Dates are valid, store them in the session
            $_SESSION['startDate'] = $startDate;
            $_SESSION['endDate'] = $endDate;
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'reserve') {
        $startDate = $_SESSION['startDate'] ?? '';
        $endDate = $_SESSION['endDate'] ?? '';

        if (empty($startDate) || empty($endDate)) {
            $error = 'Kérjük, válassza ki a dátumokat a foglaláshoz.';
        } else {
            // Check if the selected dates are available
            $selected_start = strtotime($startDate);
            $selected_end = strtotime($endDate);

            if (count($filtered_reservations) > 0) {
                foreach ($filtered_reservations as $reservation) {
                    $reservation_start = strtotime($reservation['from']);
                    $reservation_end = strtotime($reservation['to']);

                    if ($selected_start >= $reservation_start && $selected_start <= $reservation_end) {
                        $error = "foglalt";
                        header('Location: alert.php?id='. $car_id . "&status=failed" . "&model=" . $car["model"] . "&brand=" . $car["brand"] . "&from=" . $startDate . "&to=" . $endDate);
                        break;
                    }

                    if ($selected_end >= $reservation_start && $selected_end <= $reservation_end) {
                        $error = "foglalt";
                        header('Location: alert.php?id='. $car_id . "&status=failed" . "&model=" . $car["model"] . "&brand=" . $car["brand"] . "&from=" . $startDate . "&to=" . $endDate);
                        break;
                    }
                }
            }

            if (empty($error)) {
                // Create a new reservation
                $new_reservation = [
                    'from' => $startDate,
                    'to' => $endDate,
                    'user_email' => $user_email, // Replace with the actual user's email
                    'car_id' => $car['id']
                ];
                $reservation_storage->insert((object)$new_reservation);

                // Show a success message
                $success_message = 'Foglalás sikeres!';
                header('Location: alert.php?id='. $car_id . "&status=success" . "&model=" . $car["model"] . "&brand=" . $car["brand"] . "&from=" . $startDate . "&to=" . $endDate);

                // Clear the session dates
                unset($_SESSION['startDate']);
                unset($_SESSION['endDate']);

                
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
            <div class="d-flex flex-column justify-content-between w-49">
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
                        <button class="date-picker rounded-pill bg-tertiary font-weight-bold border border-dark p-2" data-toggle="modal" data-target="#datePickerModal">Dátum kiválasztása</button>
                        <form id="reserveForm" action="" method="post" class="m-0">
                            <input type="hidden" name="action" value="reserve">
                            <input type="hidden" name="startDate" value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
                            <input type="hidden" name="endDate" value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
                            <button type="submit" class="reserve rounded-pill bg-primary p-2 border border-dark font-weight-bold">Lefoglalom</button>
                        </form>
                </div>
            </div>
        </div>
            <?php if ($error): ?>
                <div class="alert alert-danger mt-5" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
    </main>

    <div class="modal fade" id="datePickerModal" tabindex="-1" role="dialog" aria-labelledby="datePickerModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="datePickerModalLabel">Dátum kiválasztása</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="action" value="save_dates">
                        <div class="form-group">
                            <label for="startDate">Kezdő dátum</label>
                            <input type="date" class="form-control"
                            name="startDate" id="startDate"
                            value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="endDate">Befejező dátum</label>
                            <input type="date" class="form-control" 
                            name="endDate"
                            id="endDate"
                            value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
                        </div>
                        
                        <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bezárás</button>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>