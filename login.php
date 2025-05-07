<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL connection
$host = "localhost";
$dbname = "tarun";
$username_db = "root"; // default in XAMPP
$password_db = "";     // default is empty

$conn = new mysqli($host, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hardcoded login credentials
$valid_username = "user";
$valid_password = "pass123";

// Logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['username'] = $username;
        header("Location: login.php");
        exit;
    } else {
        $error = "Invalid login credentials.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_form']) && isset($_SESSION['username'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO info (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    if ($stmt->execute()) {
        $message = "Form submitted and saved to database!";
    } else {
        $message = "Database error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome to bookmyread</title>
</head>
<body>

<?php
// Show messages
if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
}
if (isset($message)) {
    echo "<p style='color:green;'>$message</p>";
}
?>

<?php if (!isset($_SESSION['username'])): ?>
    <!-- Login Form -->
    <h2>Login</h2>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" name="login" value="Login">
    </form>
<?php else: ?>
    <!-- User Info Form -->
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> |
        <a href="?logout=1">Logout</a>
    </p>
    <h2>User Info Form</h2>
    <form method="post">
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <input type="submit" name="user_form" value="Submit">
    </form>
<?php endif; ?>

</body>
</html>
