<?php
include 'database-2.php';

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$sql = "SELECT * FROM books";
$result = $conn->query($sql);

$books = array();
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);
?>