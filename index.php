<?php
$host = 'db';
$user = 'root';
$pass = 'password_varo';
$db   = 'db_devops';

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi dan buat tabel jika belum ada
$conn->query("CREATE TABLE IF NOT EXISTS buku_tamu (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(50), pesan TEXT, waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

// Logika saat user menekan tombol "Kirim"
if (isset($_POST['kirim'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $pesan = $conn->real_escape_string($_POST['pesan']);
    if (!empty($nama)) {
        $conn->query("INSERT INTO buku_tamu (nama, pesan) VALUES ('$nama', '$pesan')");
    }
    header("Location: index.php"); // Refresh halaman
}

// Ambil data pengunjung
$tamu = $conn->query("SELECT * FROM buku_tamu ORDER BY waktu DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DevOps Interactive Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>🚀 Dashboard Interaktif Varo</h1>
        
        <div class="card">
            <h3>Isi Buku Tamu:</h3>
            <form method="POST">
                <input type="text" name="nama" placeholder="Nama Kamu" required><br>
                <textarea name="pesan" placeholder="Pesan singkat..."></textarea><br>
                <button type="submit" name="kirim">Kirim ke Database</button>
            </form>
        </div>

        <hr>

        <h3>5 Pengunjung Terakhir:</h3>
        <ul class="list-tamu">
            <?php while($row = $tamu->fetch_assoc()): ?>
                <li>
                    <strong><?php echo $row['nama']; ?></strong>: 
                    "<?php echo $row['pesan']; ?>" 
                    <br><small><?php echo $row['waktu']; ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>