<?php
session_start();
ob_start(); // Prevent header issues

// Check if restaurant ID exists in URL
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$restaurant_id = (int) $_GET['id'];

// If not logged in, redirect to login
if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('Please login first to make a reservation.'); window.location.href='login.php';</script>";
    exit();
}

// Save chosen restaurant in session
$_SESSION['restaurant_id'] = $restaurant_id;

// Redirect to reservation page
header("Location: reservation.php");
exit();
?>