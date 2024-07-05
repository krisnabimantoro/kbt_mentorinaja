        <?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "mentorin";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Cek koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }
        echo "Koneksi berhasil";
        

        ?>
