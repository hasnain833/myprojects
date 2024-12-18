<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "planify_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = filter_var(sanitize_input($_POST['email']), FILTER_VALIDATE_EMAIL);
    $phone = sanitize_input($_POST['phone']);
    $address = sanitize_input($_POST['address']);
    $location = sanitize_input($_POST['location']);
    $guests = filter_var($_POST['guests'], FILTER_VALIDATE_INT);
    $arrivals = sanitize_input($_POST['arrivals']);
    $leaving = sanitize_input($_POST['leaving']);

    if (empty($name)) {
        throw new Exception("Name is required.");
    }
    if (!$email) {
        throw new Exception("Invalid email address.");
    }
    if (!ctype_digit($phone)) {
        throw new Exception("Phone number must contain only digits.");
    }
    if (empty($address)) {
        throw new Exception("Address is required.");
    }
    if (empty($location)) {
        throw new Exception("Destination must be selected.");
    }
    if ($guests === false || $guests < 1) {
        throw new Exception("Number of guests must be a positive number.");
    }
    if (empty($arrivals)) {
        throw new Exception("Arrival date is required.");
    }
    if (empty($leaving)) {
        throw new Exception("Leaving date is required.");
    }
    if ($arrivals > $leaving) {
        throw new Exception("Arrival date cannot be later than the leaving date.");
    }
    
    $stmt = $conn->prepare("INSERT INTO bookings (name, email, phone, address, location, guests, arrivals, leaving) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $email, $phone, $address, $location, $guests, $arrivals, $leaving);

    if ($stmt->execute()) {
        echo "<script type='text/javascript'>
                alert('Booking successful!');
                window.location.href = 'package.html';
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
