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

    if ($nama !== '') {
        $statement = $conn->prepare('INSERT INTO buku_tamu (nama, pesan) VALUES (?, ?)');
        $statement->bind_param('ss', $nama, $pesan);
        $statement->execute();
        $statement->close();
    }

    header('Location: index.php');
    exit();
}

$totalResult = $conn->query('SELECT COUNT(*) AS total FROM buku_tamu');
$todayResult = $conn->query("SELECT COUNT(*) AS total FROM buku_tamu WHERE DATE(waktu) = CURDATE()");
$latestResult = $conn->query('SELECT nama, pesan, waktu FROM buku_tamu ORDER BY waktu DESC LIMIT 50');

$totalVisitors = (int) ($totalResult->fetch_assoc()['total'] ?? 0);
$todayVisitors = (int) ($todayResult->fetch_assoc()['total'] ?? 0);
$latestVisitors = [];

while ($row = $latestResult->fetch_assoc()) {
    $latestVisitors[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0b0b0c">
    <title>Proyek DevOps — Buku Tamu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="site-shell">
        <header class="topbar">
            <a class="brand" href="#home"><span class="brand-mark"></span><span>proyekdevops<span class="brand-muted">.dev</span></span></a>
            <nav aria-label="Navigasi utama">
                <a class="active" href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#guestbook">Guestbook</a>
            </nav>
            <a class="health-link" href="health.php">Health check <span>↗</span></a>
        </header>

        <main>
            <section id="home" class="hero">
                <div class="hero-copy">
                    <p class="availability"><span></span> AVAILABLE FOR WORK</p>
                    <h1><span>DevOps</span><span class="title-muted">Project</span></h1>
                    <p class="role">Information System_</p>
                    <p class="intro">Aplikasi buku tamu berbasis PHP dan MySQL yang dikemas dengan Docker. Jelajahi proyek, lihat status sistem, dan tinggalkan pesan di bawah.</p>
                    <div class="tags"><span>PHP</span><span>MySQL</span><span>Docker</span><span>CI/CD</span></div>
                    <div class="hero-actions">
                        <a class="button button-light" href="#guestbook">Lihat project <span>→</span></a>
                        <a class="button button-dark" href="health.php">Status sistem</a>
                    </div>
                    <p class="scroll-hint">↓ <span>explore the project below</span></p>
                </div>
                <div class="identity-wrap" aria-label="Project identity card">
                    <div class="vertical-label">PROJECT · DEVOPS</div>
                    <div class="clip"></div>
                    <div class="identity-card">
                        <div class="card-top"><span>proyekdevops.dev</span><span>ID · <?= date('Y') ?></span></div>
                        <div class="portrait"><span>PD</span><small>WEB<br>PROJECT</small></div>
                        <strong>Proyek DevOps</strong>
                        <p>Interactive web application</p>
                        <div class="barcode"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><b>MYSQL</b></div>
                    </div>
                </div>
            </section>

            <section id="about" class="about-section">
                <div class="section-label">01 / ABOUT</div>
                <div>
                    <h2>Data masuk,<br><em>pesan tersimpan.</em></h2>
                    <p>Form buku tamu ini berjalan di dalam container PHP dan terhubung ke database MySQL. Setiap pesan baru langsung muncul di daftar aktivitas, tanpa dashboard yang ramai atau ornamen yang tidak perlu.</p>
                </div>
                <div class="metrics">
                    <div><strong><?= number_format($totalVisitors, 0, ',', '.') ?></strong><span>Total entries</span></div>
                    <div><strong><?= number_format($todayVisitors, 0, ',', '.') ?></strong><span>Today</span></div>
                    <div><strong>OK</strong><span>System status</span></div>
                </div>
            </section>

            <section id="guestbook" class="guestbook-section">
                <div class="section-label">02 / GUESTBOOK</div>
                <div class="guestbook-grid">
                    <div>
                        <h2>Leave a<br><em>message.</em></h2>
                        <p class="section-note">Tulis nama dan pesan singkat. Data akan dicatat ke database proyek ini.</p>
                        <form method="POST" class="guestbook-form">
                            <label for="nama">Nama <span>*</span></label>
                            <input id="nama" type="text" name="nama" placeholder="Nama kamu" maxlength="50" required>
                            <div class="label-row"><label for="pesan">Pesan</label><span id="char-count">0 / 500</span></div>
                            <textarea id="pesan" name="pesan" placeholder="Tulis pesan..." maxlength="500"></textarea>
                            <button type="submit" name="kirim">Kirim pesan <span>→</span></button>
                        </form>
                    </div>
                    <div class="activity">
                        <div class="activity-head"><div><span class="section-label">RECENT ENTRIES</span><h3>Pengunjung terbaru</h3></div><span><?= count($latestVisitors) ?> entries</span></div>
                        <div class="activity-tools"><div class="search-box"><span>⌕</span><input id="visitor-search" type="search" placeholder="Cari nama atau pesan..." aria-label="Cari pengunjung"></div><button id="clear-search" type="button" hidden>Clear</button></div>
                        <div id="visitor-list" class="visitor-list">
                            <?php if ($latestVisitors === []): ?>
                                <div class="empty-state"><strong>Belum ada pesan.</strong><p>Jadilah yang pertama mengisi buku tamu.</p></div>
                            <?php else: ?>
                                <?php foreach ($latestVisitors as $visitor): ?>
                                    <div class="visitor-item" data-search="<?= htmlspecialchars(strtolower($visitor['nama'] . ' ' . $visitor['pesan']), ENT_QUOTES, 'UTF-8') ?>">
                                        <div class="avatar"><?= htmlspecialchars(strtoupper(substr($visitor['nama'], 0, 1)), ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="visitor-content"><div class="visitor-topline"><strong><?= htmlspecialchars($visitor['nama'], ENT_QUOTES, 'UTF-8') ?></strong><time><?= htmlspecialchars(date('d M Y, H:i', strtotime($visitor['waktu'])), ENT_QUOTES, 'UTF-8') ?></time></div><p><?= htmlspecialchars($visitor['pesan'] ?: 'Menyapa dari proyek ini.', ENT_QUOTES, 'UTF-8') ?></p></div>
                                    </div>
                                <?php endforeach; ?>
                                <div id="no-results" class="empty-state" hidden><strong>Tidak ditemukan.</strong><p>Coba kata kunci lain.</p></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer><span>© <?= date('Y') ?> proyekdevops</span><span>PHP · MySQL · Docker</span></footer>
    </div>
    <script>
        const message = document.querySelector('#pesan');
        const counter = document.querySelector('#char-count');
        const search = document.querySelector('#visitor-search');
        const clearSearch = document.querySelector('#clear-search');
        const items = [...document.querySelectorAll('.visitor-item')];
        const noResults = document.querySelector('#no-results');

        message?.addEventListener('input', () => {
            counter.textContent = `${message.value.length} / 500`;
        });

        search?.addEventListener('input', () => {
            const query = search.value.trim().toLowerCase();
            let visible = 0;
            items.forEach((item) => {
                const match = item.dataset.search.includes(query);
                item.hidden = !match;
                if (match) visible++;
            });
            if (noResults) noResults.hidden = visible !== 0 || query === '';
            if (clearSearch) clearSearch.hidden = query === '';
        });

        clearSearch?.addEventListener('click', () => {
            search.value = '';
            search.dispatchEvent(new Event('input'));
            search.focus();
        });

        const sections = [...document.querySelectorAll('main section[id]')];
        const navLinks = [...document.querySelectorAll('nav a')];
        const updateActiveNav = () => {
            const current = sections
                .filter((section) => window.scrollY + 180 >= section.offsetTop)
                .pop()?.id || 'home';
            navLinks.forEach((link) => link.classList.toggle('active', link.getAttribute('href') === `#${current}`));
        };
        window.addEventListener('scroll', updateActiveNav, { passive: true });
        updateActiveNav();
    </script>
</body>
</html>
