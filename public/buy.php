<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // associative arrays
        $stocks = [];
        $rows = [];

        // insert values into the rows array
        $rows[0]["symbol"] = "alv.de";
        $rows[1]["symbol"] = "cab";
        $rows[2]["symbol"] = "dx-y.nyb";
        $rows[3]["symbol"] = "gdx";
        $rows[4]["symbol"] = "jns";
        $rows[5]["symbol"] = "k";
        $rows[6]["symbol"] = "nvfy";
        $rows[7]["symbol"] = "oil";
        $rows[8]["symbol"] = "spy";
        $rows[9]["symbol"] = "vhc";
        $rows[10]["symbol"] = "xgti";

        // keep track of one particular row
        $row = $rows[0];

        // insert values into the stocks array
        foreach ($rows as $row)
        {
            $stock = lookup($row["symbol"]);
            if ($stock !== false)
            {
                $stocks[] = [
                    "name" => $stock["name"],
                    "price" => $stock["price"],
                    "symbol" => $row["symbol"]
                ];
            }
        }

    	// render the buy_form with appropriate variables and arrays
        render("buy_form.php", ["title" => "Buy shares", "row" => $row, "rows" => $rows, "stock" => $stock, "stocks" => $stocks]);
    }
    // if a button is clicked
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // get symbol from the select box
        $symb = $_POST["buy_symbol"];
        $num = 0;

        // validations
        if (empty($_POST["number_of_shares"]))
        {
            apologize("Please insert an amount of shares you would like to buy");
        }
        else
        {
            $num = $_POST["number_of_shares"];
        }

        // if user wants to delete one share
        if (isset( $_POST['Submit1']))
        {
            // select query from users table
            $select = CS50::query("SELECT cash FROM users
            WHERE id = ?", $_SESSION["id"]);

            // keep track of one particular row
            $selected = $select[0];

            // lookup a stock
            $stock = lookup($symb);

            // if user has enough funds to buy shares
            if (($stock["price"] * $num) < $selected["cash"])
            {
                // check if user already has shares with that symbol via select query
                $has_stock = CS50::query("SELECT symbol, shares FROM portfolios
                WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $symb);

                // if user wants to buy shares (and they don't have any with that symbol yet)
                if ((isset($has_stock[0]['shares'])) === false)
                {
                    // select query from users table
                    $select = CS50::query("SELECT cash FROM users
                    WHERE id = ?", $_SESSION["id"]);

                    // keep track of one particular row
                    $ids = $select[0];

                    // put user's new balance into a variable
                    $new_bal = ($ids["cash"] - ($stock["price"] * $num));

                    // update query to update user's cash
                    $update_cash = CS50::query("UPDATE users
                    SET cash = ?
                    WHERE id = ?", $new_bal, $_SESSION["id"]);

                    // for each purchase there needs to be a new unique id starting at 100 (like a ref number)
                    $new_id = 100;

                    // for loop to check new_id against the purchase id's and user id's that already exist in the database
                    for ($i = 0; $i < 10000; $i++)
                    {
                        $select = CS50::query("SELECT id FROM portfolios
                        WHERE id = ?", $new_id);

                        if ((isset($select[0]['id'])) !== false)
                        {
                            $new_id++;
                        }
                        else
                        {
                            $select = CS50::query("SELECT user_id FROM portfolios
                            WHERE user_id = ?", $new_id);

                            if ((isset($select[0]['id'])) !== false)
                            {
                                $new_id++;
                            }
                            else
                            {
                                break;
                            }
                        }
                    }

                    // select query from portfolios table
                    $select = CS50::query("SELECT * FROM portfolios
                    WHERE user_id = ?", $_SESSION["id"]);

                    // update query to update the users shares
                    $update_shares = CS50::query("INSERT INTO portfolios
                    (`id`, `user_id`, `symbol`, `shares`)
                    VALUES (?, ?, ?, ?)", $new_id, $_SESSION["id"], $symb, $num);

                    // insert query to log a transaction into history table
                    $history = CS50::query("INSERT INTO history
                    (`date_time`, `u_id`, `symbol`, `no_shares`, `trans_type`, `cur_bal`)
                    VALUES ((CURRENT_TIMESTAMP), ?, ?, ?, 'bought', ?)", $_SESSION["id"], $symb, $num, $new_bal);

                    // go back to index page
                    redirect("/");
                }
                // else the user has shares with that symbol already
                else
                {
                    // inner join select query from users and portfolios table
                    $select = CS50::query("SELECT users.id, users.cash, portfolios.shares, portfolios.symbol, portfolios.user_id
                    FROM (users INNER JOIN portfolios ON users.id = portfolios.user_id)
                    WHERE users.id = ? AND portfolios.symbol = ?", $_SESSION["id"], $symb);

                    // keep track of one particular row
                    $selected = $select[0];

                    // insert users new balance into a variable
                    $new_bal = ($selected["cash"] - ($stock["price"] * $num));

                    // update query to update user's cash
                    $update_cash = CS50::query("UPDATE users
                    SET cash = ?
                    WHERE id = ?", $new_bal, $_SESSION["id"]);

                    // update query to update the users shares
                    $update_shares = CS50::query("UPDATE portfolios
                    SET shares = ?
                    WHERE user_id = ? AND symbol = ?", ($selected["shares"] + $num), $_SESSION["id"], $symb);

                    // insert query to log a transaction into history table
                    $history = CS50::query("INSERT INTO history
                    (`date_time`, `u_id`, `symbol`, `no_shares`, `trans_type`, `cur_bal`)
                    VALUES ((CURRENT_TIMESTAMP), ?, ?, ?, 'bought', ?)", $_SESSION["id"], $symb, $num, $new_bal);

                    // go back to index page
                    redirect("/");
                }
            }
            // user doesn't have enough funds to buy the desires shares
            else
            {
                apologize("Insufficien funds");
            }
        }
    }
?>