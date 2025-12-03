<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "itws2110-fall2025-hebbej-quiz2";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>