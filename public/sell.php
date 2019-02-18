<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // select query for users table
        $rows = CS50::query("SELECT id, username, cash
        FROM users 
        WHERE id = ?", $_SESSION["id"]);
        
        // temp variable
        $temp = true;
        
        // select query for portfolios table
        $new = CS50::query("SELECT user_id, shares
        FROM portfolios 
        WHERE user_id = ?", $_SESSION["id"]);
        
        // if new user wants to sell shares (and they don't have any yet)
        if ((isset($new[0]['shares'])) === false)
        {
            apologize("You have no shares to sell");
        }
        // else they have shares to sell
        else
        {
            // select query for portfolios table
            $rows = CS50::query("SELECT * 
            FROM portfolios 
            WHERE user_id = ?", $_SESSION["id"]);
            
            // keep track of one particular row
            $row = $rows[0];
            
            // create a new associative array
            $stocks = [];
            
            // insert values into the associative array
            foreach ($rows as $row)
            {
                $stock = lookup($row["symbol"]);
                if ($stock !== false)
                {
                    $stocks[] = [
                        "name" => $stock["name"],
                        "price" => $stock["price"],
                        "shares" => $row["shares"],
                        "symbol" => $row["symbol"],
                        "id" => $row["id"]
                    ];
                }
            }
            // render the sell_form and appropriate variables and arrays
            render("sell_form.php", ["title" => "Sell shares", "rows" => $rows, "row" => $row, "stocks" => $stocks, "stock" => $stock]);
        }
    }
    // if buttons clicked
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // get symbol from the select box
        $symb = $_POST["sel_symbol"];
        
        // if user wants to delete one share 
        if (isset($_POST['Submit1'])) 
        {
            // select query from portfolios table
            $select = CS50::query("SELECT * FROM portfolios
            WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $symb);
            
            // keep track of one particular row
            $selected = $select[0];
            
            // make sure there are at least one share of the shares the user wants to sell
            if ($selected["shares"] > 0)
            {
                // inner join select query from users and portfolios table
                $select = CS50::query("SELECT users.id, users.cash, portfolios.shares, portfolios.symbol, portfolios.user_id
                FROM (users INNER JOIN portfolios ON users.id = portfolios.user_id) 
                WHERE users.id = ? AND portfolios.symbol = ?", $_SESSION["id"], $symb);
                
                // keep track of one particular row
                $selected = $select[0];
                
                // number of shares will decrement
                $num_shares = ($selected["shares"] - 1);
                
                // lookup current price
                $stock = lookup($symb);
                
                // put user's new balance into a variable
                $new_bal = ($stock["price"] + $selected["cash"]);
                
                // update query to update user's cash
                $update_cash = CS50::query("UPDATE users
                SET cash = ?
                WHERE id = ?", $new_bal, $_SESSION["id"]);
                
                // update query to update the users shares
                $update_shares = CS50::query("UPDATE portfolios
                SET shares = ?
                WHERE user_id = ? AND symbol = ?", $num_shares, $_SESSION["id"], $symb);
                
                // if the last share was sold, execute the delete query
                if ($num_shares == 0)
                {
                    $delete = CS50::query("DELETE FROM portfolios
                    WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $selected["symbol"]);
                }
                
                // user will only be buying one share here
                $num = 1;
                
                // insert query to log a transaction into history table
                $history = CS50::query("INSERT INTO history 
                (`date_time`, `u_id`, `symbol`, `no_shares`, `trans_type`, `cur_bal`) 
                VALUES ((CURRENT_TIMESTAMP), ?, ?, ?, 'sold', ?)", $_SESSION["id"], $symb, $num, $new_bal);
                
                // go back to index page
                redirect("/");
            }
        }
        
        // if user wants to sell all the shares of the selected share
        if (isset($_POST['Submit2'])) 
        {
            // initialize value of no of shares user will be selling
            $num = 0;
            
            // select inner join query for users and portfolios
            $select = CS50::query("SELECT users.id, users.cash, portfolios.shares, portfolios.symbol
            FROM (users INNER JOIN portfolios ON users.id = portfolios.user_id) 
            WHERE users.id = ?", $_SESSION["id"]);
            
            // keep track of particular row
            $selected = $select[0];
            
            // lookup current price
            $stock = lookup($symb);
            
            // put user's new balance into a variable
            $new_bal = (($stock["price"] * $selected["shares"]) + $selected["cash"]);
            
            // update query to update user's cash
            $update_cash = CS50::query("UPDATE users
            SET cash = ?
            WHERE id = ?", $new_bal, $_SESSION["id"]);
            
            // select query to obtain no of shares
            $shares = CS50::query("SELECT shares FROM portfolios
            WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $symb);
            
            // no of shares
            $num = $shares[0]["shares"];
            
            // insert query to log a transaction into history table
            $history = CS50::query("INSERT INTO history 
            (`date_time`, `u_id`, `symbol`, `no_shares`, `trans_type`, `cur_bal`) 
            VALUES ((CURRENT_TIMESTAMP), ?, ?, ?, 'sold', ?)", $_SESSION["id"], $symb, $num, $new_bal);
            
            // select query for portfolios table
            $select = CS50::query("SELECT * FROM portfolios
            WHERE user_id = ?", $_SESSION["id"]);
            
            // keep track of particular row
            $selected = $select[0];
            
            // delete query for all shares related to one symbol
            $delete = CS50::query("DELETE FROM portfolios
            WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $symb);
            
            // go back to index page
            redirect("/");
        }
    }
    
?>