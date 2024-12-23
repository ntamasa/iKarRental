<div class="rounded d-flex flex-column align-items-center justify-content-center bg-secondary overflow-hidden">
    <div class="img-box">
        <img src="<?php echo $car['image'] ?>" alt="Picture of <?php echo $car["brand"] . $car["model"]?>" class="w-100 img">
        <label class="price-tag m-0"><?php echo $car["daily_price_huf"] ?> Ft</label>
    </div>
    <div class="card-body d-flex flex-row justify-content-between align-items-center w-100 p-2">
        <div>
            <p class="text-primary text-medium m-0"><?php echo $car["brand"] ?> <strong><?php echo $car["model"] ?></strong></p>
            <p class="text-primary m-0 d-flex justify-content-between text-small"><?php echo $car["passengers"] ?> férőhely - <?php echo $car["transmission"] == "Manual" ? "manuális" : "automata" ?></p>
        </div>
        <button class="text-dark bg-primary font-weight-bold rounded-pill px-2 py-1 w-auto inline border border-dark" style="height: max-content;">Foglalás</button>
    </div>
</div>