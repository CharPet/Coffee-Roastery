<?php
// save-user-registration.php

// Load WordPress functions
require_once('../../../wp-load.php');

// DB credentials from wp-config
$host = '127.0.0.1'; // or 'localhost'
$user = 'root'; // or check LocalWP DB tab
$password = 'root'; // or check LocalWP DB tab
$database = 'local_kafekopteio4_userdata'; // User data database

function saveUserRegistrationData($user_id, $user_data) {
    global $host, $user, $password, $database;
    
    $conn = new mysqli($host, $user, $password, $database);
    
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        return false;
    }
    
    $stmt = $conn->prepare("INSERT INTO wp_user_registrations (user_id, username, email, first_name, last_name, place, zip, address, newsletter_subscribed, registration_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    $newsletter = isset($user_data['newsletter']) ? 1 : 0;
    
    $stmt->bind_param("issssssi", 
        $user_id,
        $user_data['username'],
        $user_data['email'],
        $user_data['first_name'],
        $user_data['last_name'],
        $user_data['place'],
        $user_data['zip'],
        $user_data['address'],
        $newsletter
    );
    
    $result = $stmt->execute();
    
    if (!$result) {
        error_log("Error saving user registration: " . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Function to get user registration data
function getUserRegistrationData($user_id) {
    global $host, $user, $password, $database;
    
    $conn = new mysqli($host, $user, $password, $database);
    
    if ($conn->connect_error) {
        return false;
    }
    
    $stmt = $conn->prepare("SELECT * FROM wp_user_registrations WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $data;
}
?>