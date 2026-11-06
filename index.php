<?php
require __DIR__ . '/config.php';

$conn = get_database_connection();

$conn->query(
    'CREATE TABLE IF NOT EXISTS buku_tamu (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(50) NOT NULL,
        pesan TEXT,
        waktu TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )'
);

if (isset($_POST['kirim'])) {
    $nama = trim((string) ($_POST['nama'] ?? ''));
    $pesan = trim((string) ($_POST['pesan'] ?? ''));

    if (!empty($nama)) {
        $statement = $conn->prepare('INSERT INTO buku_tamu (nama, pesan) VALUES (?, ?)');
        $statement->bind_param('ss', $nama, $pesan);
        $statement->execute();
        $statement->close();
    }

    header('Location: index.php');
    exit();
}

$tamu = $conn->query('SELECT nama, pesan, waktu FROM buku_tamu ORDER BY waktu DESC LIMIT 5');
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
                <input type="text" name="nama" placeholder="Nama Kamu" maxlength="50" required><br>
                <textarea name="pesan" placeholder="Pesan singkat..." maxlength="500"></textarea><br>
                <button type="submit" name="kirim">Kirim ke Database</button>
            </form>
        </div>

        <hr>

        <h3>5 Pengunjung Terakhir:</h3>
        <ul class="list-tamu">
            <?php while($row = $tamu->fetch_assoc()): ?>
                <li>
                    <strong><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?></strong>:
                    &quot;<?= htmlspecialchars($row['pesan'], ENT_QUOTES, 'UTF-8') ?>&quot;
                    <br><small><?= htmlspecialchars($row['waktu'], ENT_QUOTES, 'UTF-8') ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>