<?php

// Load WordPress functions (optional but good practice if using WordPress functions)
require_once('../../../wp-load.php');

// DB credentials from wp-config
$host = '127.0.0.1'; // or 'localhost'
$user = 'root'; // or check LocalWP DB tab
$password = 'root'; // or check LocalWP DB tab
$database = 'local_kafekopteio4'; // adjust to your DB name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$product_name = $_POST['name'] ?? '';
$product_price = $_POST['price'] ?? 0;

$stmt = $conn->prepare("INSERT INTO wp_product_orders (product_name, product_price, order_time) VALUES (?, ?, NOW())");
$stmt->bind_param("sd", $product_name, $product_price);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "OK";
} else {
    echo "Failed";
}

$stmt->close();
$conn->close();