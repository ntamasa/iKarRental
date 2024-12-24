<?php
    require_once 'jsonio.php';
    require_once 'jsonstorage.php';
    
    $storage = new JsonStorage('data/cars.json');
    $cars = $storage->all();   
    
    $res_storage = new JsonStorage('data/reservations.json');
    $reservations = $res_storage->all();

    $filteredCars = $cars;
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['reset'])) {
            header('Location: index.php');
            exit;
        }

        $filteredCars = $cars;
        if (isset($_GET['capacity']) && $_GET['capacity'] !== '0') {
            $capacity = intval($_GET['capacity']);
            $filteredCars = array_filter($filteredCars, function ($car) use ($capacity) {
                return $car['passengers'] >= $capacity;
            });
        }
        if (isset($_GET['shifter']) && $_GET['shifter'] !== '-') {
            $shifter = $_GET['shifter'];
            $filteredCars = array_filter($filteredCars, function ($car) use ($shifter) {
                return strtolower($car['transmission']) === strtolower($shifter);
            });
        }
        if (isset($_GET['min-price']) && isset($_GET['max-price']) && !empty($_GET['min-price']) && !empty($_GET['max-price'])) {
            $minPrice = intval($_GET['min-price']);
            $maxPrice = intval($_GET['max-price']);
            $filteredCars = array_filter($filteredCars, function ($car) use ($minPrice, $maxPrice) {
                return $car['daily_price_huf'] >= $minPrice && $car['daily_price_huf'] <= $maxPrice;
            });
        }
        if (isset($_GET["startDate"]) && isset($_GET["endDate"]) && !empty($_GET["startDate"]) && !empty($_GET["endDate"])) {
            $startDate = $_GET["startDate"];
            $endDate = $_GET["endDate"];

            $filteredCars = array_filter($filteredCars, function ($car) use ($startDate, $endDate, $reservations) {
                $filtered_reservations = array_filter($reservations, function($reservation) use ($car) {
                    return $reservation['car_id'] == $car['id'];
                });
                $isAvailable = true;
                foreach ($filtered_reservations as $reservation) {
                    if (strtotime($startDate) >= strtotime($reservation['from']) && strtotime($startDate) <= strtotime($reservation['to'])) {
                        $isAvailable = false;
                        break;
                    }
                    if (strtotime($endDate) >= strtotime($reservation['from']) && strtotime($endDate) <= strtotime($reservation['to'])) {
                        $isAvailable = false;
                        break;
                    }
                }
                return $isAvailable;
            });
        }
    }

    ?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
    <title>iKarRental</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="custom.css" rel="stylesheet">
</head>

<body class="bg-dark">
<?php include 'navigation.php'; ?>

<header class="py-3 px-3 mb-5">
    <h1 class="w-50 text-primary mb-4">Kölcsönözz autókat könnyedén!</h1>
    <a href="register.php" class="text-dark bg-primary font-weight-bold rounded-pill p-2 w-auto inline text-decoration-none">Regisztráció</a>
</header>

<main>
    <section class="filters">
        <form method="get" class="d-flex flex-row justify-content-end align-items-center">
            <div class="left-side d-flex flex-column justify-content-between w-100">
                <div class="first-row d-flex justify-content-end mb-2">
                        <div class="capacity d-flex flex-row align-items-center">
                            <button class="decrease inline rounded border border-secondary bg-transparent py-1 px-2 text-secondary">&minus;</button>
                            <input style="width: 5rem" type="number" class="text-primary capacity_value inline bg-transparent border border-secondary py-1 mx-2 rounded text-center" name="capacity" value="<?php echo htmlspecialchars($_GET['capacity'] ?? '0'); ?>"/>
                            <button class="increase inline rounded border border-secondary bg-transparent py-1 px-2 text-secondary">&plus;</button>
                            <label for="capacity" class="m-0 ml-2 mr-3 text-primary">férőhely</label>
                        </div>
                    <div class="date d-flex flex-row align-items-center">
                        <input type="date" class="from bg-transparent text-primary rounded border border-light p-1" name="startDate" value="<?php echo htmlspecialchars($_GET['startDate'] ?? ''); ?>"/>
                        <label class="text-primary m-0 mr-2">-tól</label>
                        <input type="date" class="to bg-transparent text-primary rounded border border-light p-1" name="endDate" value="<?php echo htmlspecialchars($_GET['endDate'] ?? ''); ?>"/>
                        <label class="text-primary m-0 mr-2">-ig</label>
                    </div>
                </div>

                <div class="second-row d-flex justify-content-end">
                    <div class="shifter d-flex flex-row align-items-center mr-3">
                        <select name="shifter" class="bg-transparent text-primary border border-secondary rounded p-1">
                            <option value="-" class="bg-dark text-primary ">Váltó típusa</option>
                            <option value="manual" class="bg-transparent bg-dark text-primary " <?php echo (isset($_GET['shifter']) && $_GET['shifter'] === 'manual') ? 'selected' : ''; ?>>Manuális</option>
                            <option value="automatic" class="bg-transparent bg-dark text-primary " <?php echo (isset($_GET['shifter']) && $_GET['shifter'] === 'automatic') ? 'selected' : ''; ?>>Automata</option>
                        </select>
                    </div>
                    
                    <div class="price d-flex flex-row align-items-center">
                        <input type="number" name="min-price" id="min-price" placeholder="14.0000" class="bg-transparent border border-secondary text-primary rounded py-1 px-2 text-center" style="width: 6rem;" value="<?php echo htmlspecialchars($_GET['min-price'] ?? ''); ?>">
                        <label class="text-primary px-2 m-0">&mdash;</label>
                        <input type="number" name="max-price" id="max-price" placeholder="21.000" class="bg-transparent border border-secondary text-primary rounded py-1 px-2 text-center" style="width: 6rem;" value="<?php echo htmlspecialchars($_GET['max-price'] ?? ''); ?>">
                        <label class="text-primary px-2 m-0">Ft</label>
                    </div>
                </div>
            </div>

            <div class="btn-box d-flex flex-column justify-content-around align-items-center">
                <button type="submit" class="mx-5 text-dark bg-primary font-weight-bold rounded-pill px-3 py-2 w-auto inline border border-dark" style="height: max-content;">Szűrés</button>
                <button type="submit" name="reset" class="text-dark bg-primary font-weight-bold rounded-pill px-3 py-2 w-auto inline border border-dark m-0" style="height: max-content;">Szűrők törlése</button>
            </div>
            </form>
    </section>

    <section class="catalog mt-5">
        <div class="container">
            <div class="row align-items-center">
                <?php if(count($filteredCars) > 0): ?>
                    <?php foreach ($filteredCars as $car): ?>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-4">
                            <?php include 'card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <h2 class="text-center text-primary">Jelenleg nincs ilyen autónk! 😔</h2>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Script for capacity filter buttons   -->
<script>
    document.querySelector('.decrease').addEventListener('click', (e) => {
        e.preventDefault();
        const capacityInput = document.querySelector('.capacity_value');
        capacityInput.value = +capacityInput.value - 1;
    });

    document.querySelector('.increase').addEventListener('click', (e) => {
        e.preventDefault();
        const capacityInput = document.querySelector('.capacity_value');
        capacityInput.value = +capacityInput.value + 1;
    });
</script>
</body>
</html>
