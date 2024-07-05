<?php
session_start();
include('connection.php');

$user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Fetch current user data
        $fetch_sql = "SELECT name, email, address, no_handphone, img_profile FROM user WHERE id_user = ?";
        $fetch_stmt = $conn->prepare($fetch_sql);
        $fetch_stmt->bind_param('i', $user_id); // 'i' indicates integer type for user_id
        $fetch_stmt->execute();
        $current_data = $fetch_stmt->get_result()->fetch_assoc();

        // Set new values from form or keep current values if form fields are empty
        $name = !empty($_POST['name']) ? $_POST['name'] : $current_data['name'];
        $email = !empty($_POST['email']) ? $_POST['email'] : $current_data['email'];
        $address = !empty($_POST['address']) ? $_POST['address'] : $current_data['address'];
        $phone = !empty($_POST['phone']) ? $_POST['phone'] : $current_data['no_handphone'];
        
        // Handle image upload if provided
        $img_profile = $current_data['img_profile'];
        if (!empty($_FILES['img']['name'])) {
            $target_dir = "../upload/profile/";
            $target_file = $target_dir . basename($_FILES["img"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["img"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["img"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                    $img_profile = basename($_FILES["img"]["name"]);
                    echo "The file ". htmlspecialchars( basename( $_FILES["img"]["name"])). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Update user data in database
        $update_sql = "UPDATE user SET name=?, email=?, address=?, no_handphone=?, img_profile=? WHERE id_user=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('sssssi', $name, $email, $address, $phone, $img_profile, $user_id); // 's' indicates string type, 'i' for integer
        $update_result = $update_stmt->execute();

        if ($update_result) {
            echo "<script>alert('Berhasil Update Data Akun'); window.location.href='../pages/profile.php';</script>";
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }

        $fetch_stmt->close();
        $update_stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
