<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ebook";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed", "status" => "error"]));
}
?>
