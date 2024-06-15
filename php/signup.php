<?php
session_start();
include('connection.php');

$name = $_POST['name'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$password = $_POST['password'];

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Assuming default role is 1 for regular users
$id_role = 1;

// Insert user data into tb_user table
$stmt = $conn->prepare("INSERT INTO tb_user (name, date_of_birth, email, address, regis_date, id_role, no_handphone) VALUES (?, ?, ?, ?, NOW(), ?, ?)");
$stmt->bind_param("ssssis", $name, $dob, $email, $address, $id_role, $phone);

if ($stmt->execute()) {
    // Registration successful, redirect to login page
    header("Location: login.html");
    exit();
} else {
    // Registration failed, handle accordingly
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
