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
    <title>Home - Web Systems Project Portal</title>
</head>

<body>

    <link rel="stylesheet" href="resources/style.css" type="text/css">
    
    <main>
        <div class="index-container">
            <p class="index-microcopy">Welcome, <?php echo($_SESSION["user_name"])?></p>
            <a class="btn" href="projects.php?showForm=false">See Projects</a>
            <a class="btn" href="projects.php?showForm=true">Register a Project</a>
        </div>
    </main>

    <!--
        This is the first page a user sees
        Put on top a title like "Web Systems Project Portal"

        Then underneath a box with some microcopy, and a login

        login button should send a request to the php server, and verify the credentials
        If you want their to be a response on-page before redirecting, use ajax for the request with a delay
    -->

    <script src="resources/script.js"></script>

</body>

</html>