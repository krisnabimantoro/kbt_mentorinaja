<?php
session_start();
include('connection.php'); // Include your database connection script

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $specialization = $_POST['bidang_mentor'];
    $offline_online = $_POST['offline_online']; // Assuming this corresponds to TB_STATUS_ID_STATUS
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    
    // Retrieve user ID from session
    $user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

    // Handle file upload for image
    $upload_dir = '../upload/mentor/';
    $image_name = $_FILES['upload_image']['name'];
    $image_tmp_name = $_FILES['upload_image']['tmp_name'];
    $target_file = $upload_dir . basename($image_name);

    if (move_uploaded_file($image_tmp_name, $target_file)) {
        try {
            // Prepare SQL statement
            $sql = "INSERT INTO mentorship (SPECIALIZATION, AVAILABILITY, RATE, DESCRIPTION, PRICE, IMG, TB_STATUS_ID_STATUS, TB_USER_ID_USER)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            // Prepare and bind parameters
            $stmt = $conn->prepare($sql);
            $availability = "Yes"; // Assuming this is fixed value for availability
            $rate = 0; // Assuming rate is not used in the form
            $stmt->bind_param("ssdsssss", $specialization, $availability, $rate, $deskripsi, $harga, $image_name, $offline_online, $user_id);

            // Execute statement
            if ($stmt->execute()) {
                // Insert successful, commit the transaction and redirect
                echo "<script>alert('Berhasil Menambahkan Mentorship'); window.location.href='../pages/searching.php';</script>";
                exit();
            } else {
                // Insert failed, handle error
                echo "Error: " . $stmt->error;
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
