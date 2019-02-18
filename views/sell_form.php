<form id="sell_form" action="sell.php" method="post">
    <fieldset>
        <div class="form-group">
            <h4>Choose the symbol of the share you would like to sell:</h4>
             <select name="sel_symbol" style="font-size:120%">
                 <?php foreach ($stocks as $stock): for ($i = 0; $i < $stock["shares"]; $i++) { ?>
				  <option value=<?= $stock["symbol"] ?>>Symbol: <?= $stock["symbol"] ?>  |  Current Price: $<?=number_format(($stock["price"]), 2) ?></option>
				  <?php } endforeach; ?>
			</select> 
        </div>
        <div class="form-group">
            <button action="post" class="btn btn-default" type="submit" name="Submit1">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Sell this one share
            </button>
            <button class="btn btn-default" type="submit" name="Submit2">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Sell all shares with this symbol
            </button>
        </div>
    </fieldset>
</form>