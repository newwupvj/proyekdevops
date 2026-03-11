<?php
$host = 'db'; // Nama service di docker-compose
$user = 'root';
$pass = 'password_varo';
$db   = 'db_devops';

$conn = new mysqli($host, $user, $pass, $db);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Catat kunjungan ke database
$conn->query("CREATE TABLE IF NOT EXISTS kunjungan (id INT AUTO_INCREMENT PRIMARY KEY, waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
$conn->query("INSERT INTO kunjungan () VALUES ()");

// Ambil total kunjungan
$result = $conn->query("SELECT COUNT(*) as total FROM kunjungan");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>DevOps Database</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>🚀 Website Varo dengan Database</h1>
    <p>Halaman ini telah diakses sebanyak: <strong><?php echo $row['total']; ?></strong> kali.</p>
</body>
</html>