<?php
session_start();
include('connection.php');


$email = $_POST['email'];
$password = $_POST['password'];
 
$sql ="select * from tb_user where email='$email' and password='$password'";

$result = mysqli_query($conn, $sql);
$row = mysqli_num_rows($result);

if ($row>0) {
    header("Location: https://mentorinaja.my.id/wordpress/home-search/");
    
}else {
    // Login failed, show alert
    echo "<script>alert('Password salah'); window.location.href='../login.html';</script>";
}

echo $row;


$conn->close();
?>