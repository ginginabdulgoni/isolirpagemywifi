
# Isolir Page MyWiFi

Halaman isolir tagihan pelanggan yang elegan dan profesional, dibuat untuk menampilkan status layanan pelanggan dan memberikan kemudahan dalam mengecek tagihan melalui API terpusat MyWiFi.

## 📦 Fitur

- Cek tagihan pelanggan berdasarkan nomor layanan (`no_services`)
- Menampilkan status tagihan, jatuh tempo, dan informasi pelanggan
- Desain responsif dan profesional menggunakan Bootstrap 5
- Informasi perusahaan otomatis dari `.env`
- Validasi dan pesan error langsung dari API pusat
- Tidak menyimpan data — semua data diambil secara real-time dari API pusat

## ⚙️ Instalasi

1. Clone repository:

```bash
git clone https://github.com/ginginabdulgoni/isolirpagemywifi.git
cd isolirpagemywifi
```

2. Install dependency (opsional jika menggunakan fitur `Dotenv`):

```bash
composer install
```

3. Salin file `.env`:

```bash
cp .env.example .env
```

4. Edit `.env` dan sesuaikan dengan informasi perusahaan dan API kamu.

## 📄 Contoh `.env`

```
API_KEY="your-api-key-here"
API_BASE_URL="https://your-subdomain.mywifi.web.id"

COMPANY_NAME="MyWiFi"
COMPANY_ADDRESS="Jl. Contoh No. 123, Jakarta"
COMPANY_PHONE="0812-3456-7890"
COMPANY_EMAIL="info@mywifi.web.id"
```

## 🚀 Cara Pakai

1. Akses halaman `index.php` dari browser atau hosting kamu.
2. Masukkan **No Layanan**, lalu klik **Cek Tagihan**.
3. Sistem akan menampilkan data tagihan jika ada.

## 📞 Kontak

- Website: [1112-project.com](https://1112-project.com)
- Sistem Billing: [member.mywifi.web.id](https://member.mywifi.web.id)
- Author: [Gingin Abdul Goni](https://github.com/ginginabdulgoni)

---

> Dibuat dengan ❤️ oleh 1112 Project.
