<?php
session_start();
include('../php/connection.php');


$user_id = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Pembayaran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/History.css">
</head>
<body>
    <div class="container">
        <a href="./searching.php">
            <button class="back-button"><i class="fas fa-arrow-left"></i> Kembali</button>
        </a>
        <h1>History Pembayaran</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Nama Mentor</th>
                        <th>Jenis Mentor Spesifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "
                        SELECT
                            p.created_at,
                            p.price,
                            p.payment_method,
                            u.name AS mentor_name,
                            m.specialization
                        FROM
                            pemesanan p
                        JOIN
                            user u ON p.mentor_id = u.id_user
                        JOIN
                            mentorship m ON p.mentorship_id = m.id_mentorship
                        WHERE
                            p.user_id = ?
                        ORDER BY
                            p.created_at DESC";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>Rp. " . number_format($row['price'], 0, ',', '.') . "</td>";
                        echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['mentor_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['specialization']) . "</td>";
                        echo "</tr>";
                    }

                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
