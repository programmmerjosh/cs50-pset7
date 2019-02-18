<?php
    
    // configuration
    require("../includes/config.php"); 
    
    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render quote_form
        render("quote_form.php", ["title" => "Submit Quote"]);
    }
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validation
        if (empty($_POST["symbol"]))
        {
            apologize("You must insert a symbol");
        }
        
        // lookup stock
        $stock = lookup($_POST["symbol"]);
        
        // check if valid symbol
        if ($stock == false)
        {
            apologize("Invalid symbol");
        }
        else
        {
            // render the quote_price form with appropriate variables and arrays
            render("quote_price.php", ["title" => "Requested Quote", "symbol" => $stock["symbol"], "name" => $stock["name"], "price" => $stock["price"]]);
        }
    } 
     
?>