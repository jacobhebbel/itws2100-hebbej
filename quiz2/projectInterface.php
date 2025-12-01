<?php

    $USERS_TABLE = "";
    $PROJECTS_TABLE = "";

    $mysqli = new mysqli("localhost", "username", "password", "database");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Implement php logic here

    // projectInterface.php validates read/writes to the projects table.

    // GET => return the html table of the projects table
    // POST => validate upload (unique project name, project members are students in db)

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'GET') {

        // read projects table
        $projectsQuery = `SELECT * FROM ${PROJECTS_TABLE};`;
        $projectsResult = $mysqli->query($projectsQuery);

        // construct html table

        // respond

    }

    if ($method == 'POST') {

        // validate body

        if (!isset($_POST['name'])
        || !isset($_POST['desc'])
        || !isset($_POST['team[]'])) {

            // respond with missingParameter error message

        }

        $projectName = isset($_POST['name']) ? $_POST['name'] : NULL;
        $projectDesc = isset($_POST['desc']) ? $_POST['desc'] : NULL;
        $projectTeam = isset($_POST['team']) ? $_POST['team[]'] : NULL;

        if (count($projectTeam) < 3) {

            // respond with tooFewMembers error message

        }

        $projectsQuery = `SELECT * in $PROJECTS_TABLE WHERE name=$projectName;`;
        $projectsResult = $mysqli->query($projectsQuery);
        if ($projectsResult.numRows != 0) {

            // respond with invalidName error message

        }

        // insert to table

        $teamAsCommaStr = join($projectTeam, ', ');
        $insertQuery = `INSERT INTO $PROJECTS_TABLE (name, description, team) VALUES ($projectName, $projectDesc, $teamAsCommaStr);`;
        $insertResult = $mysqli->query($insertQuery);

        // verify response from mySQL
        
        if ($insertResult == ??) {
            
            // respond with successfulInsertion message

        }

        // respond with failed Insertion message

    }
?>