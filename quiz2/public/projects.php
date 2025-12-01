<?php
session_start();

// ensures logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$TABLE_VIEW = 0;
$FORM_VIEW = 1;

$view = $_GET['view'];

if ($view == $TABLE_VIEW) { loadTable(); }
?>

<!doctype HTML>
<html>

<head>
    <title> Projects - Web Sys Project Portal </title>
</head>

<body>

</body>

</html>