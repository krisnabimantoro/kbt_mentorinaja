<?php
session_start();
include('../php/connection.php');

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $sql = "
        SELECT 
            tm.id_mentorship,
            tm.specialization,
            tm.availability,
            tm.rate,
            tm.description,
            tm.price,
            tm.tb_status_id_status AS status,
            tm.tb_user_id_user,
            tm.img,
            u.name AS mentor_name,
            u.address AS location,
            u.img_profile AS profile_pic,
            u.no_handphone
        FROM 
            mentorship tm
        JOIN 
            user u ON tm.tb_user_id_user = u.id_user
        WHERE 
            tm.id_mentorship = ?
            AND u.tb_role_id_role = 2";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id); // 'i' indicates integer type for $id
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $whatsappUrl = "https://wa.me/" . htmlspecialchars($row['no_handphone']);
        $_SESSION['user_id'] = $row['tb_user_id_user'];
        $_SESSION['mentorship_id'] = $id;
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Mentorship Detail</title>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;600&family=Montserrat:wght@300;600&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="../styles/MentorshipDetail.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        </head>
        <body>
            

            <div class="container">
                <header class="header">
                    <div class="profile-pic">
                    <img src="../img/profile.png" alt="Profile" class="profile-pic" >
                    </div>
                    <div class="mentor-info">
                        <h1><?php echo htmlspecialchars($row['mentor_name']); ?></h1>
                        <div class="location">
                            <span class="icon">📍</span>
                            <p><?php echo htmlspecialchars($row['location']); ?></p>
                        </div>
                        <div class="mentorship">Mentorship</div>
                    </div>
                </header>

                <main class="main-content">
                    <h2><?php echo htmlspecialchars($row['specialization']); ?></h2>
                    <div class="subject-image">
                        <img src="../upload/mentor/<?php echo htmlspecialchars($row['img']); ?>" alt="<?php echo htmlspecialchars($row['specialization']); ?>">
                    </div>
                    <p class="description">
                        <?php echo htmlspecialchars($row['description']); ?>
                    </p>
                </main>

                <section class="mentorship-detail">
                    <div class="ketersediaan-group">
                        <div class="ketersediaan">Ketersediaan</div>
                        <div class="tersedia"><?php echo htmlspecialchars($row['availability']) === 'Yes' ? 'Tersedia' : 'Tidak Tersedia'; ?></div>
                    </div>
                    <div class="lokasi-group">
                        <div class="lokasi">Lokasi</div>
                        <div class="alamat"><?php echo htmlspecialchars($row['location']); ?></div>
                    </div>
                    <div class="metode-belajar-group">
                        <div class="metode-belajar">Metode Belajar</div>
                        <div class="offline"><?php echo htmlspecialchars($row['status']) === '2' ? 'Offline' : 'Online'; ?></div>
                    </div>

                    <div class="button-group">
                        <form id="paymentForm" action="../php/save_order.php" method="post">
    <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($row['price']); ?>">
    <input type="hidden" name="mentorship_id" value="<?php echo htmlspecialchars($row['id_mentorship']); ?>">
    <input type="hidden" name="mentor_id" value="<?php echo htmlspecialchars($row['tb_user_id_user']); ?>">
    <select name="payment_method">
        <option value="Dana">Dana</option>
        <option value="Gopay">Gopay</option>
        <option value="VA">VA</option>
    </select>
    <button type="submit" class="price-button">Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></button>
</form>

                        <div class="whatsapp">
                            <a href="<?php echo $whatsappUrl; ?>" target="_blank">
                                <button class="whatsapp-button">Whatsapp</button>
                            </a>
                        </div>
                    </div>
                </section>
            </div>

        </body>
        </html>

        <?php
    } else {
        echo "<p>No mentorship details found.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<p>No mentorship ID provided.</p>";
}
?>
