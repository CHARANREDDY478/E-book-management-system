<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include "database-2.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["message" => "Invalid request method", "status" => "error"]);
    exit;
}

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (!$data) {
    echo json_encode(["message" => "Invalid JSON", "status" => "error"]);
    exit;
}

$email = $data["email"] ?? null;
$password = $data["password"] ?? null;
$role = $data["role"] ?? null;

if (!$email || !$password || !$role) {
    echo json_encode(["message" => "Missing required fields", "status" => "error"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["message" => "Invalid email format", "status" => "error"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? AND role = ?");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION["user_id"] = $user_id;
        $_SESSION["email"] = $email;
        $_SESSION["role"] = $role;

        echo json_encode(["message" => "Login successful", "status" => "success"]);
    } else {
        echo json_encode(["message" => "Incorrect password", "status" => "error"]);
    }
} else {
    echo json_encode(["message" => "User not found", "status" => "error"]);
}

$stmt->close();
$conn->close();
?>
