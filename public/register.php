<?php

    // configuration
    require("../includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render the register form
        render("register_form.php", ["title" => "Register"]);
    }
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // validations
        if ((empty($_POST["username"])) || (empty($_POST["password"])))
        {
            apologize("Username or password is blank. Please insure both have values");
            $_POST["username"] = "";
            $_POST["password"] = "";
            $_POST["confirmation"] = "";
        }
        else if (($_POST["password"]) != ($_POST["confirmation"]))
        {
            apologize("password does not match the confirmation password");
        }
        
        // insert query for new user
        $success = CS50::query("INSERT IGNORE INTO users (username, hash, cash) VALUES(?, ?, 10000.0000)", $_POST["username"], password_hash($_POST["password"], PASSWORD_DEFAULT));
        
        // make sure user doesn't already exist
        if ($success == 0)
        {
            apologize("User already exists");
            $_POST["username"] = "";
            $_POST["password"] = "";
            $_POST["confirmation"] = "";
        }
        // it is a new user
        else
        {
            // select query for users table
            $arrs = CS50::query("SELECT * FROM users WHERE username = ?", $_POST["username"]);
            
            // keep track of one particular row
            $arr = $arrs[0];
            
            // remember that user's now logged in by storing user's ID in session
            $_SESSION["id"] = $arr["id"];
            
            // redirect to portfolio
            redirect("/");
        }
    }
    
?>