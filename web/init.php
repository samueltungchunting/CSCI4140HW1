<?php
    //check cookie for only admin
    if (isset($_COOKIE['user_session']) && $_COOKIE['user_session'] === 'admin') {
        //check if post request is made
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goahead'])) {
            include "db_connect.php";
            // delete all records from album table and photots in uploads folder
            $sql = "DELETE FROM album";
            pg_query($conn, $sql);
            $files = glob('uploads/*'); // get all file names
            foreach($files as $file){ // iterate files
                if(is_file($file))
                    unlink($file); // delete file
            }
            echo "<br/><h4>System Initialized Successfully</h4>";
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goback'])) {
            header('Location: index.php');
            exit();
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="init.css" />
</head>
<body>
    <div class="init_page">
        <h1>System Initialization!!!!</h1>
        <p>Important: All data will be deleted if go ahead</p>
        <form action="init.php" method="post" class="init_form">
            <a href="/index.php">
                <input type="button" name="goback" value="Go Back" id="goBackBtn">
            </a>
            <input type="submit" name="goahead" value="Please Go Ahead" id="goAheadBtn">
        </form>
    </div>
</body>
</html>