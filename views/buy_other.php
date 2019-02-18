<form action="buy_other.php" method="post">
    <fieldset>
        <div class="form-group">
            <h4>Insert the symbol you want to buy here:</h4>
             <input class="form-control" name="symbol" placeholder="Symbol" type="text"/>
        </div>
        <div class="form-group">
            <h4>How many of these shares would you like to buy?</h4>
            <input class="form-control" name="number_of_shares" placeholder="Amount" type="text"/>
        </div>
        <div class="form-group">
            <button action="post" class="btn btn-default" type="submit" name="Submit1">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Buy share(s)
            </button>
        </div>
        <div>
        Recommended: Go to <a href="quote.php">Quote</a> page to get the symbol price before you purchase stock
        </div>
    </fieldset>
</form>