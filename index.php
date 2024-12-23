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

<header class="py-3 px-3">
    <h1 class="w-50 text-primary mb-4">Kölcsönözz autókat könnyedén!</h1>
    <a href="register.php" class="text-dark bg-primary font-weight-bold rounded-pill p-2 w-auto inline text-decoration-none">Regisztráció</a>
</header>
<main>
    <section class="filters">
        <form class="d-flex flex-row justify-content-end align-items-center">
            <div class="left-side d-flex flex-column justify-content-between w-100">
                <div class="first-row d-flex justify-content-end mb-2">
                        <div class="capacity d-flex flex-row align-items-center">
                            <button class="decrease inline rounded border border-secondary bg-transparent py-1 px-2 text-secondary">&minus;</button>
                            <input style="width: 5rem" type="number" value="0" class="text-primary capacity_value inline bg-transparent border border-secondary py-1 mx-2 rounded text-center" name="capacity"/>
                            <button class="increase inline rounded border border-secondary bg-transparent py-1 px-2 text-secondary">&plus;</button>
                            <label for="capacity" class="m-0 ml-2 mr-3 text-primary">férőhely</label>
                        </div>
                    <div class="date d-flex flex-row align-items-center">
                        <input type="date" class="from bg-transparent text-primary rounded border border-light p-1"/>
                        <label class="text-primary m-0 mr-2">-tól</label>
                        <input type="date" class="to bg-transparent text-primary rounded border border-light p-1"/>
                        <label class="text-primary m-0 mr-2">-ig</label>
                    </div>
                </div>

                <div class="second-row d-flex justify-content-end">
                    <div class="shifter d-flex flex-row align-items-center mr-3">
                        <select name="shifter" class="bg-transparent text-primary border border-secondary rounded p-1">
                            <option value="-" class="bg-dark text-primary ">Váltó típusa</option>
                            <option value="manual" class="bg-transparent bg-dark text-primary ">Manuális</option>
                            <option value="automatic" class="bg-transparent bg-dark text-primary ">Automata</option>
                        </select>
                    </div>
                    
                    <div class="price d-flex flex-row align-items-center">
                        <input type="number" name="min-price" id="min-price" placeholder="14.0000" class="bg-transparent border border-secondary text-primary rounded py-1 px-2 text-center" style="width: 6rem;">
                        <label class="text-primary px-2 m-0">&mdash;</label>
                        <input type="number" name="max-price" id="max-price" placeholder="21.0000" class="bg-transparent border border-secondary text-primary rounded py-1 px-2 text-center" style="width: 6rem;">
                        <label class="text-primary px-2 m-0">Ft</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="mx-5 text-dark bg-primary font-weight-bold rounded-pill px-3 py-2 w-auto inline border border-dark" style="height: max-content;">Szűrés</button>
        </form>
    </section>

    <section class="catalog"></section>
</main>


<div class="container">
    <?php
    // Dynamic content for cars
    $json_data = '[
        
        // Add more cars as needed
    ]';

    $cars = json_decode($json_data, true);

    foreach ($cars as $car) {
        echo '<div class="car-card">';
        echo '<img src="' . $car['image'] . '" alt="' . $car['model'] . '">';
        echo '<div class="price">' . $car['price'] . '</div>';
        echo '<div class="details">' . $car['model'] . '<br>' . $car['seats'] . ' - ' . $car['transmission'] . '</div>';
        echo '<button class="booking-btn">Foglalás</button>';
        echo '</div>';
    }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
