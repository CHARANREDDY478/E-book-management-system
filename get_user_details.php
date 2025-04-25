<?php
session_start();
include 'database-2.php'; // Include your database connection

$userId = $_SESSION['email']; // Assuming you store user ID in session
$query = "SELECT email, phone, role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'email' => $user['email'], 'phone' => $user['phone'], 'role' => $user['role']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User  not found.']);
}
?>