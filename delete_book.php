<?php
include 'database-2.php';

$id = $_GET['id'];

$sql = "DELETE FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

echo json_encode(array('message' => 'Book deleted successfully'));
?>