<?php
include 'database-2.php'; // Your DB connection file

if (isset($_GET['book'])) {
    $bookName = $_GET['book'];
    $stmt = $conn->prepare("SELECT * FROM books WHERE name = ?");
    $stmt->bind_param("s", $bookName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "book" => $row]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>
