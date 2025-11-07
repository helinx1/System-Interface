<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: signup.php');
    exit();
}

$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$contact_number = trim($_POST['contact_number']);
$special_status = trim($_POST['special_status']);
$year = trim($_POST['year']);
$month = trim($_POST['month']);
$day = trim($_POST['day']);

$birthday = $year . "-" . $month . "-" . $day;


if (!preg_match('/^[^\s@]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|icloud\.com|aol\.com|protonmail\.com|zoho\.com|yandex\.com|mail\.com)$/i', $email)) {
    $_SESSION['error'] = 'Invalid email format. Please enter a valid email address';
    header('Location: signup.php');
    exit();
}


if (!preg_match('/^((\+63\d{10})|(09\d{9}))$/', $contact_number)) {
    $_SESSION['error'] = 'Invalid Contact Number. Please enter a valid Contact number.';
    header('Location: signup.php');
    exit();
}

// Check if email already exists
$stmt = $conn->prepare("SELECT customer_id FROM customer WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['error'] = 'Email already exists. Please use a different email.';
    header('Location: signup.php');
    exit();
}
$stmt->close();


$hash_password = password_hash($password, PASSWORD_DEFAULT);


$stmt = $conn->prepare("INSERT INTO customer (first_name, last_name, email, password, contact_number, special_status, birthday) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $first_name, $last_name, $email, $hash_password, $contact_number, $special_status, $birthday);

if ($stmt->execute()) {
    echo "<script>alert('Signup successful! Please login now.'); window.location.href='login.php';</script>";
} else {
    echo "<script>alert('Error occurred. Please try again.'); window.location.href='signup.php';</script>";
}

$stmt->close();
$conn->close();
?>
