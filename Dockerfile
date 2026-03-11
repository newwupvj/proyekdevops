# Gunakan image Nginx versi ringan (Alpine) sebagai dasar
FROM nginx:alpine

# Salin file website Anda dari laptop ke dalam folder server di kontainer
COPY . /usr/share/nginx/html

# Beritahu Docker bahwa kontainer ini akan berjalan di port 80
EXPOSE 80
