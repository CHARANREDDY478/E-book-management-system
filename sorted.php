<?php
include 'database-2.php';

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Get the category from the query parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM books";
if ($category) {
    $sql .= " WHERE category = ?";
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if ($category) {
    $stmt->bind_param("s", $category);
}
$stmt->execute();
$result = $stmt->get_result();

$books = array();
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
?>