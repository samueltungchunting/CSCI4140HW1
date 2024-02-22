<?php
    if (!isset($_COOKIE['user_session'])) {
        header('Location: login.php');
        exit();
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout']) && isset($_COOKIE['user_session'])) {
        setcookie('user_session', '', time() - 86400, "/");
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="logout_page">
        <h1>Logout</h1>
        <p>Are you sure you want to logout?</p>
        <form action="logout.php" method="post">
            <input type="submit" name="logout" value="Logout" class="logout_btn">
        </form>
    </div>
</body>
</html>
