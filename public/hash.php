<?php

    // configuration
    require("../includes/config.php");
    
    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // render the hash_form 
            render("hash_form.php", ["title" => "Change Password"]);
    }
    // if buttons clicked
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // old and new password variables
        $old = "";
        $new = "";
        
        // validations
        if ((empty($_POST["current"])) || (empty($_POST["new"])) || (empty($_POST["confirm"])))
        {
            apologize("Please ensure that all fields are filled in");
        }
        else if (($_POST["new"]) != ($_POST["confirm"]))
        {
            apologize("New password does not match the confirmation password");
        }
        // if all fields are filled in as well as new password being the same as confirmed new password
        else
        {
            // new password
            $new = $_POST["new"];

            // select query from users table
            $select = CS50::query("SELECT * FROM users
            WHERE id = ?", $_SESSION["id"]);
            
            // keep track of one particular row
            $selected = $select[0];
            
            // make sure current password is the user's actual current password
            if (password_verify($_POST["current"], $selected["hash"]))
            {
                // update query to update user's new password and hash it
                $success = CS50::query("UPDATE users
                SET hash = ?
                WHERE id = ?", password_hash($new, PASSWORD_DEFAULT), $_SESSION["id"]);
                
                // redirect to portfolio
                redirect("/");
            }
            else
            {
                apologize("current password is incorrect");
            }
        }
    }
    
?>