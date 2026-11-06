# Proyek DevOps - Buku Tamu

Aplikasi PHP + MySQL yang siap dijalankan secara konsisten dengan Docker dan
divalidasi otomatis oleh GitHub Actions.

## Jalankan lokal

```bash
cp .env.example .env
# Ganti semua password di .env untuk penggunaan nyata
docker compose up --build
```

Buka <http://localhost:8888>. Status aplikasi dapat diperiksa di
<http://localhost:8888/health.php>.

## Operasional

- Database memakai named volume `mysql_data`, sehingga data tetap ada saat
  container dibuat ulang.
- Kredensial hanya dibaca dari environment; jangan commit `.env`.
- `pull_request` menjalankan pemeriksaan sintaks PHP, konfigurasi Compose, dan
  build image.
- Push ke `main` menjalankan pipeline yang sama lalu mem-publish image ke Docker
  Hub dengan tag `latest` dan commit SHA.

## Konfigurasi Docker Hub

Tambahkan secrets berikut di repository:

- `DOCKERHUB_USERNAME`
- `DOCKERHUB_TOKEN`

Image hasil build menggunakan nama
`<DOCKERHUB_USERNAME>/web-varo:<tag>`.
