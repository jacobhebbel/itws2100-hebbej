<?php
    session_start();
    
    require "db.php";
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $USERS_TABLE = "users";
    
    $ERR_SIG = false;
    $ERR_MSG = "";

    function validateId($id) {
        global $conn, $USERS_TABLE;
        $query = "SELECT * FROM `$USERS_TABLE` WHERE userId = ?;";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) return false;  // prepare failed

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        // Return true if at least one row exists
        $isValid = $result && ($result->num_rows > 0);

        if ($isValid) {
            $row = $result->fetch_assoc();
            $_SESSION['user_name'] = $row['nickName'];
            return true;
        }

        return false;

    }

    function validatePass($id, $pass) {
        global $conn, $USERS_TABLE;
        $query = "SELECT * FROM `$USERS_TABLE` WHERE userId = ?;";
        $stmt = $conn->prepare($query);
        if (!$stmt) return false;
        
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $stmt->close();

        if ($row = $result->fetch_assoc()) {
            // Compare submitted password with hashed password
            return password_verify($pass, $row['hash']);
        }

        return false;  // user not found or password mismatch
    }

    // php functionality
    // login should send a request to itself on button click,
    // if POST & SUCCESS: redirect to index.php
    // if POST & ID_NOT_FOUND: redirect to register.php
    // else: display an error message

    $method = $_SERVER["REQUEST_METHOD"];
    if ($method == "POST") {
        
        $validRequest = isset($_POST['id']) && ($_POST['id'] != "") && isset($_POST['pass']) && ($_POST['pass'] != "");
        
        if (!$validRequest) {
            $ERR_SIG = true;
            $ERR_MSG = "Please fill in all fields before submitting.";
        } else {

            $user = intval($_POST['id']);
            $pass = $_POST['pass'];

            $validId = validateId($user);
            if (!$validId) {
                // implies user needs to register
                header("Location: register.php");
                exit();
            }
            
            $validPass = validatePass($user, $pass);
            if (!$validPass) {
                $ERR_SIG = true;
                $ERR_MSG = "Invalid login credentials.";
            } else {

                // now user is logged in
                $_SESSION["user_id"] = $user;
                
                // now redirect to index.php
                header("Location: index.php");
                exit();
            }
        }
    }

?>

<!doctype HTML>
<head>
    <title>Login - Web Systems Project Portal</title>
</head>

<body>

    <link rel="stylesheet" href="resources/style.css" type="text/css">
    
    <main>
        <div class="login-container">
            <p class="login-microcopy">Welcome to the F25 Web Sys Project Portal. Please login to continue.</p>
            
            <?php
                global $ERR_SIG, $ERR_MSG;
                if ($ERR_SIG) {
                    echo "$ERR_MSG";
                }
            ?>
            
            <p class="login-error"></p>
            <form class="login-form" method="POST" action="login.php">
                <input type="text" name="id" placeholder="UserId">
                <input type="text" name="pass" placeholder="Password">
                <button type="submit" class="login-button">Login</button>
            </form>
        </div>
    </main>

</body>

</html>