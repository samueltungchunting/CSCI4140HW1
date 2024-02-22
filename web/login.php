<?php
$users = [
    'admin' => 'minda123', // Administrator
    'Student' => 'csci4140sp24', // Normal user
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "<p style='color: red;'>Error: Password cannot be empty</p>";
        exit();
    }
    // Check if the submitted credentials match pre-registered users
    if (array_key_exists($username, $users) && $users[$username] == $password) {
        $_SESSION['username'] = $username;
        setcookie('user_session', $username, time() + (86400), "/"); // 86400 = 1 day
        header('Location: index.php');
        exit();
    } else {
        $error_message = "Invalid username or password";
        header("Location: index.php?error=$error_message");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login_page">
        <h1>Login</h1>
        <?php if (isset($error_message)) echo "<p>$error_message</p>"; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="login_form">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
