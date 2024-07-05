<?php
session_start();
include('connection.php');

$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute the query to fetch user data by email
$stmt = $conn->prepare("SELECT id_user, password, name, tb_role_id_role FROM user WHERE email = ?");

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Check if a user with the provided email exists
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id_user, $hashed_password, $name,$role);
    $stmt->fetch();
    
    // Verify the password
    if (password_verify($password, $hashed_password)) {
        // Password is correct, set session variables
        $_SESSION['id_user'] = $id_user;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        // Redirect to a protected page or dashboard
        echo "<script>alert('Login Successful'); window.location.href='../pages/searching.php';</script>";
        exit();
    } else {
        // Invalid password
        echo "<script>alert('Invalid Email or Password'); window.location.href='../pages/login.html';</script>";
    }
} else {
    // No user found with the provided email
    echo "<script>alert('Invalid Email or Password'); window.location.href='../pages/login.html';</script>";
}

$stmt->close();
$conn->close();
?>
