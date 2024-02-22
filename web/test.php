<?php 
    include "db_connect.php";

    $result = $conn->query("SELECT * FROM public.album WHERE id = 1");
    foreach ($result as $row) {
        echo($GLOBAL);
        echo "<pre>";
        print_r($row); 
        echo "<pre>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>
        Hi, I am a test page

    </h1>
</body>
</html>