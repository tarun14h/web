<?php
// MySQL connection
$host = "localhost";
$dbname = "tarun";
$username_db = "root";      // default in XAMPP
$password_db = "";          // default is empty

$conn = new mysqli($host, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - BookMyRead</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7fb; color: #333; }
        .container { max-width: 400px; margin: 100px auto; padding: 30px; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { padding: 10px 20px; background-color: #4a90e2; color: white; border: none; border-radius: 5px; cursor: pointer; width: 100%; }
        button:hover { background-color: #357ABD; }
        .error { color: red; text-align: center; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="container">
        <h2>Create Account</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" name="register">Register</button>
        </form>
    </div>

    <div class="footer">
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>
