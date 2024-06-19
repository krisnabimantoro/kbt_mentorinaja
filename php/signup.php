<?php
session_start();
include('connection.php');

$name = $_POST['name'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$password = $_POST['password'];
$id_role = $_POST['role'];

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Assuming default role is 1 for regular users
$id_role = 1;

// Insert user data into tb_user table
$stmt = $conn->prepare("INSERT INTO tb_user (name, date_of_birth, email, address, regis_date, id_role, no_handphone, password) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");
$stmt->bind_param("ssssiss", $name, $dob, $email, $address, $id_role, $phone, $password);


if ($stmt->execute()) {
    // Registration successful, redirect to login page
    echo "<script>alert('Berhasil Membuat Akun'); window.location.href='../login.html';</script>";
    // header("Location: ../login.html");
    exit();
} else {
    // Registration failed, handle accordingly
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
