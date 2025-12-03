<?php
    
    session_start();
    
    require "db.php";
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $USERS_TABLE = "users";

    $ERR_SIG = false;
    $ERR_MSG = "";

    function updateUsersTable() {
        $id = intval($_POST['id']);
        $firstName = $_POST['fname'];
        $lastName = $_POST['lname'];
        $nickName = $_POST['nname'];
        $password = password_hash($_POST['pass'], 1);
        
        global $conn, $USERS_TABLE;
        $query = "INSERT INTO 
        `$USERS_TABLE` (userId, firstName, lastName, nickName, hash)
        VALUES (?, ?, ?, ?, ?);";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) return false;

        $stmt->bind_param("issss", $id, $firstName, $lastName, $nickName, $password);
        $stmt->execute();
        $stmt->close();
    }

    function validateId($id) {
        echo "hi";

        global $conn, $USERS_TABLE;
        $query = "SELECT * FROM `$USERS_TABLE` 
        WHERE userId = ?;";

        echo "$query";
        echo "test";
        var_dump($query);

        $stmt = $conn->prepare($query);
        if (!$stmt) return false;

        echo 'you should see me';

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        
        $numRows = $stmt->num_rows;
        $stmt->close();

        // Return true if no rows match
        return $numRows == 0;
    }

    function validatePass($pass) {
        
        // to keep the hashing algorithm effective,
        // limit password length to 8-12 characters

        return strlen($pass) >= 8 && strlen($pass) <= 12;
    }

    // php functionality
    // login should send a request to itself on button click,
    // if POST & SUCCESS: redirect to index.php
    // if POST & ID_NOT_FOUND: redirect to register.php
    // else: display an error message

    $method = $_SERVER["REQUEST_METHOD"];

    if ($method == "GET") {
        // do nothing lol

    } else {
        
        // handles missing parameter
        $validRequest = isset($_POST['id']) && ($_POST['id'] != "") && 
                        isset($_POST['fname']) && ($_POST['fname'] != "") &&
                        isset($_POST['lname']) && ($_POST['lname'] != "") &&
                        isset($_POST['nname']) && ($_POST['nname'] != "") &&
                        isset($_POST['pass']) && ($_POST['pass'] != "");

        if (!$validRequest) {
            $ERR_SIG = true;
            $ERR_MSG = "Please fill in every field before pressing register";
        } else {
            // handles invalid id
            $validId = validateId(intval($_POST['id']));
            if (!$validId) {
                $ERR_SIG = true;
                $ERR_MSG = "Please pick a different id";
            } else {
                // handles invalid password
                $validPass = validatePass($_POST['pass']);
                if (!$validPass) {
                    $ERR_SIG = true;
                    $ERR_MSG = "Please check that your password is 8-12 characters (inclusive)";
                } else {
                    // updates database
                    updateUsersTable();

                    // confirms user is logged in
                    $_SESSION["user_id"] = $_POST['id'];
                    $_SESSION["user_name"] = $_POST['nname'];
                        
                    // redirects to index.php
                    header("Location: index.php");
                    exit();
                }
            }
        }
    }
?>

<!doctype HTML>
<head>
    <title>Register - Web Systems Project Portal</title>
</head>

<body>

    <link rel="stylesheet" href="resources/style.css" type="text/css">
    
    <main>
        <div class="login-container">
            <p class="login-microcopy">It seems like you don't have an account. Please register below</p>
            
            <?php
                global $ERR_SIG, $ERR_MSG;
                if ($ERR_SIG) {
                    echo "$ERR_MSG";
                }
            ?>
            
            <form class="login-form" method="POST" action="register.php">
                <input type="text" name="id" placeholder="Choose a userId">
                <input type="text" name="fname" placeholder="First Name">
                <input type="text" name="lname" placeholder="Last Name">
                <input type="text" name="nname" placeholder="Nick Name">
                <input type="text" name="pass" placeholder="Choose a password (Length 8-12 characters)">
                <button type="submit" class="login-button">Register</button>
            </form>
        </div>
    </main>

    <!--
        This is the first page a user sees
        Put on top a title like "Web Systems Project Portal"

        Then underneath a box with some microcopy, and a login

        login button should send a request to the php server, and verify the credentials
        If you want their to be a response on-page before redirecting, use ajax for the request with a delay
    -->
</body>

</html>