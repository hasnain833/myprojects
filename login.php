<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "planify_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            header("Location: index.html");
            exit();
        } else {
            header("Location: Login.html?password_error=Invalid password");
        }
    } else {
        header("Location: Login.html?email_error=Email not found");
    }
}

$conn->close();
?>
