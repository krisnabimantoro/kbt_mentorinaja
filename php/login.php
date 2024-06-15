<?php
session_start();
include('connection.php');


$email = $_POST['email'];
$password = $_POST['password'];
 
$sql ="select * from tb_user where email='$email' and password='$password'";

$result = mysqli_query($conn, $sql);
$row = mysqli_num_rows($result);
echo $row;


$conn->close();
?>
