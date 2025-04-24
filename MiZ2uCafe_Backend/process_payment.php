<?php
session_start();
require 'db_connect.php'; // Database connection

// Validate user session
if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please login first.");
}

// Initialize error array
$errors = [];

// Sanitize inputs
$amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT);
$method = $_POST['method'] ?? '';
$remarks = htmlspecialchars($_POST['remarks'] ?? '');

// Validate amount
if (!$amount || $amount <= 0) {
    $errors[] = "Invalid payment amount";
}

// Validate payment method
if (!in_array($method, ['credit_card', 'touch_n_go'])) {
    $errors[] = "Invalid payment method selected";
}

// Process payment if no errors
if (empty($errors)) {
    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("
            INSERT INTO payments (user_id, amount, method, remarks)
            VALUES (:user_id, :amount, :method, :remarks)
        ");
        
        // Bind parameters
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':amount' => $amount,
            ':method' => $method,
            ':remarks' => $remarks
        ]);
        
        // Redirect to success page
        header("Location: payment_success.php?id=" . $pdo->lastInsertId());
        exit;
        
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        $errors[] = "Payment processing failed. Please try again.";
    }
}

// Handle errors
$_SESSION['payment_errors'] = $errors;
header("Location: payment.php");
exit;