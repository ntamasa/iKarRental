<?php
    if (count($reservation) > 0) {
        $car = $reservation["car"];
    }


    $price = floor($car["daily_price_huf"]/1000) . '.' . ($car["daily_price_huf"]%1000 == 0 ? '000' : $car["daily_price_huf"]%1000);
?>

<div class="rounded d-flex flex-column align-items-center justify-content-center bg-secondary overflow-hidden">
    <div class="img-box">
        <?php if ($user !== null && $user["role"] === "admin"): ?>
            <form method="post">
                <?php if (!empty($reservation)): ?>
                    <input type="hidden" name="res_id" value="<?php echo htmlspecialchars($reservation["_id"]); ?>">
                <?php else: ?>
                    <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car["_id"]); ?>">
                <?php endif; ?>
                
                <button name="delete" type="submit" class="btn-del bg-danger text-primary rounded py-1 px-2 border border-dark">Törlés</button>

                <?php if (empty($reservation)): ?>
                    <a href="modify.php?id=<?php echo $car["_id"] ?>" class="btn-modify bg-lightest text-dark rounded py-1 px-2 border border-dark">Szerkeszt</a>
                <?php endif; ?>
            </form>
        <?php endif; ?>
        
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
            <?php if ($user !== null && $user["role"] === "admin"): ?>
                <?php if (!empty($reservation)): ?>
                    <p class="text-primary text-medium m-0"><?php echo $reservation["user_email"] ?></p>
                <?php endif; ?>
            <?php endif; ?>

        </div>
        <a href="car.php?id=<?php echo $car["_id"] ?>" class="text-dark bg-primary font-weight-bold rounded-pill px-2 py-1 w-auto inline border border-dark <?php if (count($reservation) > 0): ?>
            hidden
        <?php endif; ?>" style="height: max-content;">Foglalás</a>
    </div>
</div>