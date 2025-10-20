<?php
session_start();
include 'db_connect.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    echo "<script>alert('Please enter email and password.'); window.location.href='login.php';</script>";
    exit();
}

// Select user by email
$stmt = $conn->prepare("SELECT customer_id, first_name, last_name, password, special_status, birthday FROM customer WHERE email = ?");
if (!$stmt) {
    echo "<script>alert('Server error. Please try again later.'); window.location.href='login.php';</script>";
    exit();
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Email not found
if ($result->num_rows === 0) {
    echo "<script>alert('This account does not exist yet.'); window.location.href='login.php';</script>";
    exit();
}

$row = $result->fetch_assoc();

// Check password
if (!password_verify($password, $row['password'])) {
    echo "<script>alert('Incorrect password.'); window.location.href='login.php';</script>";
    exit();
}

// Successful login
session_regenerate_id(true);
$_SESSION['customer_id'] = $row['customer_id'];
$_SESSION['customer_name'] = $row['first_name'] . ' ' . $row['last_name'];
$_SESSION['special_status'] = $row['special_status'];
$_SESSION['birthday'] = $row['birthday'];

echo "<script>alert('Login successful!'); window.location.href='home.php';</script>";
exit();
?>