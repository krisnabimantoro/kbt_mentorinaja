<?php
session_start();
include('connection.php');
$user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $mentorship_id = $_POST['mentorship_id'];
    $mentor_id = $_POST['mentor_id'];
    $price = $_POST['total_price'];
    $payment_method = $_POST['payment_method'];

    $sql = "INSERT INTO pemesanan (user_id, mentorship_id, mentor_id, price, payment_method) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiids', $user_id, $mentorship_id, $mentor_id, $price, $payment_method); // 'iiids' indicates integer, integer, integer, double, string types
    $result = $stmt->execute();

    if ($result) {
        echo "<script>alert('Pemesanan berhasil!'); window.location.href='../pages/searching.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
