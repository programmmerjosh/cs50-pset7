<?php

    // configuration
    require("../includes/config.php");
    
    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $rows = CS50::query("SELECT id, username, cash
        FROM users 
        WHERE id = ?", $_SESSION["id"]);
        
        $temp = true;
        
        $new = CS50::query("SELECT user_id, shares
        FROM portfolios 
        WHERE user_id = ?", $_SESSION["id"]);
        
        
        
        if ((isset($new[0]['shares'])) === false)
        {
            $users = CS50::query("SELECT username, cash FROM users WHERE id = ?", $_SESSION["id"]);
            
            $user = $users[0];
            
            render("portfolio.php", ["title" => "Portfolio", "temp" => $temp, "username" => $user["username"], "cash" => $user["cash"]]);
        }
        else
        {
            $rows = CS50::query("SELECT users.username, users.cash, portfolios.symbol, portfolios.shares 
            FROM (users INNER JOIN portfolios ON users.id = portfolios.user_id) 
            WHERE user_id = ?", $_SESSION["id"]);
            
            $row = $rows[0];
            $positions = [];
            
            foreach ($rows as $row)
            {
                $stock = lookup($row["symbol"]);
                if ($stock !== false)
                {
                    $positions[] = [
                        "name" => $stock["name"],
                        "price" => $stock["price"],
                        "shares" => $row["shares"],
                        "symbol" => $row["symbol"]
                    ];
                }
            }
            $temp = false;
            render("portfolio.php", ["title" => "Portfolio", "temp" => $temp, "positions" => $positions, "username" => $row["username"], "cash" => $row["cash"]]);
        }
        
    }

?>