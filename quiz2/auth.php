<?php

    $USERS_TABLE = "";
    $PROJECTS_TABLE = "";

    $mysqli = new mysqli("localhost", "username", "password", "database");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // implement php logic here

    // auth.php is server-side only. Its job is to validate login/registration attempts
    
    // GET => login from login.php. Check hashed passwords and user ids.
    //  * If userId missing, tell user to make an account
    //  * If password is wrong, tell user 'invalid login'

    // POST => registration from registration.php. Check data is consistent and no repeat userIDs
    //  * Indicate which fields are malformed

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'GET') {

        $userId = isset($_GET['id']) ? $_GET['id'] : NULL;
        $pass = isset($_GET['password']) ? $_GET['password'] : NULL;

        if ($userId == NULL || $pass == NULL) {
            // respond with missingParameter message
        }

        
        $usersQuery = `SELECT * FROM $USERS_TABLE where id=$userId`;
        $usersResult = $mysqli->query($uersQuery);
        if ($usersResult.numRows == 0) {
            // respond with missingId message, redirect to register.php
        }
        
        $hash = hashFunction($pass);
        if ($hash != $usersResult.hash) {
            // respond with invalidPassword message
        }

        // respond with validLogin message

    }
?>