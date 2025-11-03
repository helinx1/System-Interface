<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate passwords match
    if ($new_password !== $confirm_password) {
        header("Location: reset_password.php?error=passwords_dont_match");
        exit();
    }

    // Check if email exists and verify old password
    $stmt = $conn->prepare("SELECT customer_id, password FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify old password
        if (password_verify($old_password, $user['password'])) {
            // Check if new password is same as old password
            if (password_verify($new_password, $user['password'])) {
                header("Location: reset_password.php?error=same_password");
                exit();
            }
            
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE customer SET password = ? WHERE customer_id = ?");
            $stmt->bind_param("si", $hashed_password, $user['customer_id']);
            $stmt->execute();
            echo "<script>alert('Password updated successfully!'); window.location.href='login.php';</script>";
            exit();
        } else {
            header("Location: reset_password.php?error=incorrect_password");
            exit();
        }
    } else {
        header("Location: reset_password.php?error=email_not_found");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>