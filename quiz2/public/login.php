<?php
    
    require "db.php";
    $USERS_TABLE = "";

    function validateId($id) {
        
        $query = `SELECT * FROM $USERS_TABLE WHERE id = ?;`;
        $statement = $conn->prepare($query);
        $statement->bind_param('i', $id);

    }

    function validatePass($id, $pass) {

        $query = `SELECT * FROM $USERS_TABLE WHERE id = ?;`;
        $statement = $conn->prepare($query);
        $statement->bind_param('i', $id);
    }

    // php functionality
    // login should send a request to itself on button click,
    // if POST & SUCCESS: redirect to index.php
    // if POST & ID_NOT_FOUND: redirect to register.php
    // else: display an error message

    $method = $_SERVER["RESPONSE_METHOD"];

    // if GET, skip
    if ($method == "GET") {

        // return the login page

    }

    // implicit ELSE ensures this logic executes on POST

    // validate POST

    $validRequest = isset($_POST['id'] && isset($_POST['pass'])) ? true : false;
    if (!$validRequest) {

        // missing parameter error

    }

    $user = $_POST['id'];
    $pass = $_POST['pass'];

    $validId = validateId($user);
    if (!$validId) {

        // invalid id error

    }

    $validPass = validatePass($user, $pass);
    if (!$validPass) {

        // invalid password error

    }

    // now user is logged in
    $_SESSION["user_id"] = $user;

    // now redirect to index.php
    header("Location: index.php");
    exit();
?>

<!doctype HTML>
<head>
    <title>Login</title>
</head>

<body>
    <!--
        This is the first page a user sees
        Put on top a title like "Web Systems Project Portal"

        Then underneath a box with some microcopy, and a login

        login button should send a request to the php server, and verify the credentials
        If you want their to be a response on-page before redirecting, use ajax for the request with a delay
    -->
</body>

</html>