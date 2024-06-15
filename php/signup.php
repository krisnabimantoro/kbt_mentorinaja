<?php
session_start();
include('connection.php');

$name = $_POST['name'];
$dob = $_POST['dob'];
$email = $_POST['email'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$password = $_POST['password'];


$hashed_password = password_hash($password, PASSWORD_DEFAULT);
 
$sql ="insert into from tb_user (name, date_of_birth, email, address, regis_date, no_handphone,password) VALUES (:name,:dob, :email, :address, NOW(), :no_handphone, :password
";

$stmt = $db->prepare($sql);
$params = array(
    ":name" => $name,
    ":dob" => $username,
    ":email" => $password,
    ":address" => $email,
    ":no_handphone" => $phone,
    ":password" => $password
);
$saved = $stmt->execute($params);

if($saved) header("Location: login.php");

// $result = mysqli_query($conn, $sql);
// $row = mysqli_num_rows($result);

// if ($row>0) {
//     header("Location: https://mentorinaja.my.id/wordpress/home-search/");
    
// }else {
//     // Login failed, show alert
//     echo "<script>alert('Password salah'); window.location.href='../login.html';</script>";
// }

// echo $row;


$conn->close();
?>