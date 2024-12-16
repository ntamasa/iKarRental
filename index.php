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

<body>
<?php include 'navigation.php'; ?>
<header>
    <h1>Kölcsönözz autókat könnyedén!</h1>
</header>

<div class="auth-buttons">
    <a href="#">Bejelentkezés</a>
    <a href="#">Regisztráció</a>
</div>

<div class="filters">
    <input type="date" name="from" id="from" placeholder="Dátum kezdete">
    <input type="date" name="to" id="to" placeholder="Dátum vége">
    <select name="carType" id="carType">
        <option value="all">Váltó típusa</option>
        <option value="manual">Manuális</option>
        <option value="automatic">Automata</option>
    </select>
    <input type="number" name="priceRange" id="priceRange" placeholder="Ár (Ft)" min="14000" max="21000" step="500">
    <button>Szűrés</button>
</div>

<div class="container">
    <?php
    // Dynamic content for cars
    $cars = [
        ['model' => 'Nissan Altima', 'price' => '14.500 Ft', 'seats' => '5 férőhely', 'transmission' => 'automata', 'image' => 'car1.jpg'],
        ['model' => 'Nissan Altima', 'price' => '14.500 Ft', 'seats' => '5 férőhely', 'transmission' => 'automata', 'image' => 'car2.jpg'],
        // Add more cars as needed
    ];

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
