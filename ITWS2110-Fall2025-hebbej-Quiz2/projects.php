<?php 
    session_start();
    require "db.php";

    $USERS_TABLE = "users";
    $PROJECTS_TABLE = "projects";
    $PROJECTS_USERS_TABLE = "projectMembership";

    $ERR_SIG = false;
    $ERR_MSG = "";

    // ensures user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit();
    }

    if (!isset($_SESSION["ERR_SIG"])) {
        $_SESSION["ERR_SIG"] = false;
    }
    
    function getTableData(&$pArr, &$uArr) {
        global $conn, $USERS_TABLE, $PROJECTS_TABLE, $PROJECTS_USERS_TABLE;
        
        $projectsQuery = "SELECT projectId, `name`, `description`
        FROM `$PROJECTS_TABLE`
        ORDER BY projectId ASC;";
        
        $usersQuery = "SELECT `$PROJECTS_USERS_TABLE`.projectId, `$USERS_TABLE`.firstName, `$USERS_TABLE`.lastName 
        FROM `$USERS_TABLE`
        INNER JOIN `$PROJECTS_USERS_TABLE`
        ON `$PROJECTS_USERS_TABLE`.memberId = `$USERS_TABLE`.userId 
        ORDER BY `$PROJECTS_USERS_TABLE`.projectId ASC;";

        $result = $conn->query($projectsQuery);
        if (!$result) return false;
        $numProjects = $result->num_rows;

        while ($row = $result->fetch_assoc()) {
            $pArr[$row['projectId']] = array($row['name'], $row['description']);
        }

        $result = $conn->query($usersQuery);
        while ($row = $result->fetch_assoc()) {
            $uArr[$row['projectId']][] = array($row['firstName'], $row['lastName']);
        }
    }

    function validateTeam($team) {
        $peopleIds = explode(', ', $team);
        $numPeople = count($peopleIds);

        return ($numPeople >= 3);
    }

    function updateProjectsTable() {
        global $conn, $PROJECTS_TABLE;

        $query = "INSERT INTO `$PROJECTS_TABLE` 
            (`name`, `description`)
            VALUES (?, ?);";

        echo 'hello';
        $stmt = $conn->prepare($query);
        if (!$stmt) return false;

        echo 'hi';
        $stmt->bind_param("ss", $_POST["name"], $_POST["desc"]);
        $stmt->execute();
        $result = $conn->insert_id;
        $stmt->close();

        return $result;
    }

    function updateMembershipsTable($ids, $projectId) {
        global $conn, $PROJECTS_USERS_TABLE;

        if (empty($ids)) return false;

        $ids = array_map('intval', $ids);

        $query = "INSERT INTO
        `$PROJECTS_USERS_TABLE` 
        (projectId, memberId)
        VALUES (?, ?);
        ";

        $stmt = $conn->prepare($query);
        if (!$stmt) return false;

        foreach ($ids as $id) {
            $stmt->bind_param("ii", $projectId, $id);
            $stmt->execute();
        }

        $stmt->close();
        return true;
    }

    function validateIds($ids) {
        global $conn, $USERS_TABLE;

        if (empty($ids)) return false;

        $ids = array_map('intval', $ids);

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $query = "
            SELECT userId 
            FROM `$USERS_TABLE`
            WHERE userId IN ($placeholders)
        ";

        $stmt = $conn->prepare($query);

        $types = str_repeat('i', count($ids));
        $stmt->bind_param($types, ...$ids);

        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) return false;
        $stmt->close();

        return $result->num_rows === count($ids);
    }

    $useFormView = $_REQUEST['showForm'] == 'true';
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // do nothing lol
    } else {

        $validRequest = isset($_POST['name']) && ($_POST['name'] != "") &&
                        isset($_POST['desc']) && ($_POST['desc'] != "") &&
                        isset($_POST['team']) && ($_POST['team'] != "");
    
        if (!$validRequest) {
            
            $_SESSION['ERR_SIG'] = true;
            $_SESSION['ERR_MSG'] = "Please fill out all fields before pressing register.";
            
            header("Location: /projects.php?showForm=true");
            exit();
            
        } else {

            $validTeam = validateTeam($_POST['team']);
            if (!$validTeam) {
                
                $_SESSION['ERR_SIG'] = true;
                $_SESSION['ERR_MSG'] = "Your team must have at least 3 people on it.";
                
                header("Location: /projects.php?showForm=true");
                exit();
                
            } else {

                $teamIds = explode(', ', $_POST['team']);
                $validIds = validateIds($teamIds);
                if (!$validIds) {
                    
                    $_SESSION['ERR_SIG'] = true;
                    $_SESSION['ERR_MSG'] = "One or more team ids were invalid.";
                    
                    //header("Location: /projects.php?showForm=true");
                    //exit();
                
                } else {

                    $_SESSION['ERR_SIG'] = false;

                    
                    $projectId = updateProjectsTable();
                    
                    updateMembershipsTable($teamIds, $projectId);

                    //header("Location: /projects.php?showForm=false");
                    //exit();
                }
            }
        }
    }
?>

<!doctype HTML>
<head>
    <title>Projects - Web Systems Project Portal</title>
</head>

<body>

    <link rel="stylesheet" href="/resources/style.css" type="text/css">
    
    <main>
        <div class="login-container">
            <?php
                if ($useFormView) {
                    echo '<p class="login-microcopy">Use this form to register your project.</p>';
                    
                    if ($_SESSION['ERR_SIG']) {
                        $msg = $_SESSION["ERR_MSG"];
                        echo "<p class='login-microcopy'>$msg</p>";
                    }
                    
                    echo '
                    <form class="login-form" method="POST" action="/projects.php?showForm=false">
                        <input type="text" name="name" placeholder="What\'s your project name?">
                        <input type="text" name="desc" placeholder="Describe your project">
                        <input type="text" name="team" placeholder="Add the id\'s of your team (comma-separated)">
                        <button type="submit" class="login-button">Register</button>
                    </form>';

                } else {
                    $projectsArray = array();
                    $usersArray = array();
                    getTableData($projectsArray, $usersArray);

                    $table = "
                    <table>
                        <tr>
                            <th>Project Name</th>
                            <th>Project Description</th>
                            <th>Team Members</th>
                        </tr>
                    ";          // html for making a table
                    
                    foreach ($projectsArray as $pid => $projectInfo) {
                        // each iteration adds a new row to the table
                        $name = $projectInfo[0];
                        $desc = $projectInfo[1];
                        $team = $usersArray[$pid];
                        
                        $teamStr = "";
                        
                        foreach($team as $tIdx => $teammate) { 
                            $teamStr .= implode(' ', $teammate) . ', '; }
                        
                        $table .= "<tr>
                        <th>$name</th>
                        <th>$desc</th>
                        <th>$teamStr</th>
                        </tr>";
                    }

                    $table .= "</table>";
                    echo($table);
                }
            ?>
        </div>
    </main>
</body>

</html>