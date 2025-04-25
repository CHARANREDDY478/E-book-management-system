<?php
// Include database connection
include "database-2.php";

// Set headers for CORS and JSON response
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Get category from URL
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

$response = [];

if ($category) {
    $query = "SELECT * FROM books WHERE category='$category'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response[] = [
                'id' => $row['id'],
                'book_name' => htmlspecialchars($row['book_name']),
                'cost' => htmlspecialchars($row['cost'])
            ];
        }
    } else {
        $response['message'] = "No books available in this category.";
    }
} else {
    $response['error'] = "Category not specified.";
}

$conn->close();

// Output JSON response
echo json_encode($response);
?>
