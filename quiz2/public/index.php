<?php 
session_start();


// ensures user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
    // php logic goes here
    // This page just links to projects.php, so I don't think it will need too much
?>

<!doctype HTML>
<head>
    <title>index</title>
</head>

<body>

    <!--
        Implement a 2-button interface for interacting with projects.php
        Button 1 directs to a project registration page
        Button 2 directs to a project viewing page
        
        maybe the button tells the server what kind of content to display on projects.php?
    -->
</body>