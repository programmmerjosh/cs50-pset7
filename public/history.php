<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // inner join select query from users and history table
        $rows = CS50::query("SELECT users.cash, history.*
        FROM (users INNER JOIN history ON users.id = history.u_id)
        WHERE users.id = ?", $_SESSION["id"]);

        // only render history.php if there is a transaction history
        if ((isset($rows[0]['symbol'])) === false)
        {
            apologize("No history to show");
        }
        else
        {
            // keep track of one particular row
            $row = $rows[0];
            
            // render history.php with appropriate variables and arrays
            render("history.php", ["title" => "History", "row" => $row, "rows" => $rows]);
        }
    }
    // if buttons clicked
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // if user wants to delete their transaction history 
        if (isset($_POST['Submit1'])) 
        {
            // delete query for history table
            $delete = CS50::query("DELETE FROM history
            WHERE u_id = ?", $_SESSION["id"]);
            
            // no more transaction history to show
            apologize("No history to show");
        }
    }
    
?>