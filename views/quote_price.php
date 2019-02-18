<form action="quote.php" method="post">
    <fieldset>
        <div class="form-group">
            <p><h3>Name: </h3><?=$name?> </p>
            <p><h3>Symbol: </h3><?=$symbol?> </p>
            <p><h3>Price: </h3>$<?=number_format($price, 2)?> </p>
        </div>
    </fieldset>
</form>