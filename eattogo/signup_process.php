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

// Check if email already exists
$stmt = $conn->prepare("SELECT customer_id FROM customer WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Email already exists. Please use a different email.'); window.location.href='signup.php';</script>";
    exit();
}
$stmt->close();

// Hash password
$hash_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new customer
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