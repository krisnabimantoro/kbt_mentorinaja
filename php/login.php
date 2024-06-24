<?php
session_start();
require 'connection.php';  // Ensure this file correctly sets up the MySQLi connection and stores it in $conn

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Email and Password are required!";
    } else {
        // Prepare the SQL statement to fetch the user data by email
        $sql = 'SELECT * FROM tb_user WHERE email = ?';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the user data
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Start the session and redirect to the searching page
                $_SESSION['user_id'] = $user['ID_USER'];
                $_SESSION['email'] = $user['EMAIL'];
                $_SESSION['role'] = $user['id_role'];
                header("Location: ../pages/searching.php");
                exit();
            } else {
                echo "<script>alert('Password salah'); window.location.href='../pages/login.html';</script>";
            }
        } else {
            echo "<script>alert('Email tidak ditemukan'); window.location.href='../pages/login.html';</script>";
        }

        $stmt->close();
    }
}

$conn->close();
?>
