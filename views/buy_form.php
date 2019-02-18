<form action="buy.php" method="post">
    <fieldset>
        <div class="form-group">
            <h4>Select a stock you would like to buy:</h4>
             <select name="buy_symbol" style="font-size:120%">
                 <?php foreach ($stocks as $stock): ?>
				  <option value=<?= $stock["symbol"] ?>>Symbol: <?= $stock["symbol"] ?>  |  Current Price: $<?=number_format(($stock["price"]), 2) ?></option>
				  <?php endforeach; ?>
			</select>
        </div>
        <br/>
        <div class="form-group">
            <h4>How many shares would you like to buy?</h4>
            <input class="form-control" name="number_of_shares" placeholder="Amount" type="text"/>
        </div>
        <div class="form-group">
            <button action="post" class="btn btn-default" type="submit" name="Submit1">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Buy share(s)
            </button>
        </div>
        <div>
            <a href="buy_other.php">Click here</a> if you didn't find a stock you were looking for.
        </div>
    </fieldset>
</form>