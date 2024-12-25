<?php
    if (count($reservation) > 0) {
        $car = $reservation["car"];
    }


    $price = floor($car["daily_price_huf"]/1000) . '.' . ($car["daily_price_huf"]%1000 == 0 ? '000' : $car["daily_price_huf"]%1000);
?>

<div class="rounded d-flex flex-column align-items-center justify-content-center bg-secondary overflow-hidden">
    <div class="img-box">
        <img src="<?php echo $car['image'] ?>" alt="Picture of <?php echo $car["brand"] . $car["model"]?>" class="w-100 img">
        
        <?php if(count($reservation) < 1): ?>
            <label class="price-tag m-0"><?php echo $price ?> Ft</label>
        <?php else: ?>
            <label class="res-date m-0"><?php echo $reservation["from"]?>&dash;<?php echo $reservation["to"] ?></label>
        <?php endif; ?>
    </div>
    <div class="card-body d-flex flex-row justify-content-between align-items-center w-100 p-2">
        <div>
            <p class="text-primary text-medium m-0"><?php echo $car["brand"] ?> <strong><?php echo $car["model"] ?></strong></p>
            <p class="text-primary m-0 d-flex justify-content-between text-small"><?php echo $car["passengers"] ?> férőhely - <?php echo $car["transmission"] == "Manual" ? "manuális" : "automata" ?></p>
        </div>
        <a href="car.php?id=<?php echo $car["id"] ?>" class="text-dark bg-primary font-weight-bold rounded-pill px-2 py-1 w-auto inline border border-dark <?php if (count($reservation) > 0): ?>
            hidden
        <?php endif; ?>" style="height: max-content;">Foglalás</a>
    </div>
</div>