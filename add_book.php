<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'database-2.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Ensure request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('message' => 'Invalid request method.'));
    exit;
}

// Ensure all fields exist
if (!isset($_POST['school'], $_POST['category'], $_POST['fee'], $_FILES['cover_image')) {
    echo json_encode(array('message' => 'Missing required fields.'));
    exit;
}

$school = $_POST['school'];
$category = $_POST['category'];
$fee = $_POST['fee'];
$coverImage = $_FILES['cover_image'];

// Validate file uploads
if ($coverImage['error'] !== UPLOAD_ERR_OK ) {
    echo json_encode(array('message' => 'File upload error.'));
    exit;
}

// Validate file types
$allowedImageTypes = ['image/jpeg', 'image/png'];


if (!in_array($coverImage['type'], $allowedImageTypes)) {
    echo json_encode(array('message' => 'Cover image must be a JPG or PNG file.'));
    exit;
}



// Ensure upload directory exists
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Upload files
$coverImagePath = $uploadDir . basename($coverImage['name']);


if (!move_uploaded_file($coverImage['tmp_name'], $coverImagePath) ||
     {
    echo json_encode(array('message' => 'Failed to upload files.'));
    exit;
}

// Insert into database
$sql = "INSERT INTO books (school, category,fee, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('message' => 'SQL prepare error: ' . $conn->error));
    exit;
}

$stmt->bind_param("sssssss", $school, $category, $fee, $coverImagePath);

if ($stmt->execute()) {
    echo json_encode(array('message' => 'school added successfully'));
} else {
    echo json_encode(array('message' => 'Database insert error: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>
