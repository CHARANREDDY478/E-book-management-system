<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ebook";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed: " . $conn->connect_error]));
}

// Get JSON data from frontend
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["message" => "Invalid JSON data received"]);
    exit;
}

// Extract form data
$email = isset($data["email"]) ? $data["email"] : "";
$password = isset($data["password"]) ? $data["password"] : "";
$role = isset($data["role"]) ? $data["role"] : "";
$phone = isset($data["phone"]) ? $data["phone"] : "";


// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["message" => "Invalid email format"]);
    exit;
}

// Validate password length
if (strlen($password) < 6) {
    echo json_encode(["message" => "Password must be at least 8 characters"]);
    exit;
}

// Validate phone number (must be 10 digits)
if (!preg_match("/^\d{10}$/", $phone)) {
    echo json_encode(["message" => "Invalid phone number. Must be 10 digits."]);
    exit;
}

// Check if email already exists
$email_check_sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($email_check_sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["message" => "Email already exists"]);
    exit;
}
$stmt->close();

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert new user into database
$sql = "INSERT INTO users (email, password, role, phone) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $email, $hashed_password, $role, $phone,);

if ($stmt->execute()) {
    echo json_encode(["message" => "Signup successful"]);
} else {
    echo json_encode(["message" => "Signup failed: " . $conn->error]);
}

// Close the database connection
$stmt->close();
$conn->close();
?>
