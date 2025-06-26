<?php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Internet Diisolir | <?= $_ENV['COMPANY_NAME'] ?? 'Perusahaan Anda' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-custom {
            background-color: #ffffff;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .notice-header {
            color: #dc3545;
            font-weight: 700;
            font-size: 2rem;
        }

        .notice-sub {
            color: #007bff;
            font-weight: 600;
            font-size: 1.2rem;
        }

        .box-alert {
            background-color: #eaf4ff;
            border-left: 5px solid #007bff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .footer-contact {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #555;
        }

        .wifi-icon {
            max-width: 120px;
        }
    </style>
</head>

<body>
    <div class="container text-center mt-5">
        <img src="https://cdn-icons-png.flaticon.com/512/565/565547.png" alt="Isolir" class="wifi-icon mb-3">
        <div class="notice-header">MOHON MAAF</div>
        <div class="notice-sub">Layanan Internet Anda Saat Ini Diisolir</div>

        <div class="box-alert mt-4 text-start mx-auto" style="max-width: 600px;">
            <p><strong>Pelanggan Internet yang Terhormat,</strong></p>
            <p>Kami informasikan bahwa layanan internet Anda saat ini sedang <strong>diisolir</strong> secara otomatis oleh sistem billing kami.</p>
            <p>Dimohon untuk <strong>melakukan pembayaran tagihan</strong> agar layanan internet Anda dapat <strong>kembali normal</strong>.</p>
            <p>Untuk menghindari ketidaknyamanan ini, mohon lakukan pembayaran <strong>tepat waktu</strong> setiap bulan.</p>
            <p>Jika Anda memiliki pertanyaan, silakan hubungi admin layanan kami melalui kontak di bawah.</p>
            <p class="text-center mb-0">~ Terima kasih ~</p>
        </div>

        <div class="card card-custom mt-5 p-4 mx-auto" style="max-width: 600px;">
            <h5 class="mb-3 text-start">Cek Tagihan Anda</h5>
            <div class="input-group mb-3">
                <input type="text" id="no_services" class="form-control" placeholder="Masukkan No Layanan">
                <button onclick="cekTagihan()" class="btn btn-primary">Cek Tagihan</button>
            </div>
            <div id="hasil"></div>
        </div>

        <div class="footer-contact mt-4">
            <?php if (!empty($_ENV['COMPANY_NAME'])) : ?>
                <p><strong><?= $_ENV['COMPANY_NAME'] ?></strong></p>
            <?php endif; ?>

            <?php if (!empty($_ENV['COMPANY_ADDRESS'])) : ?>
                <p><?= $_ENV['COMPANY_ADDRESS'] ?></p>
            <?php endif; ?>

            <?php if (!empty($_ENV['COMPANY_PHONE']) || !empty($_ENV['COMPANY_EMAIL'])) : ?>
                <p>
                    <?php if (!empty($_ENV['COMPANY_PHONE'])) : ?>
                        Telp: <?= $_ENV['COMPANY_PHONE'] ?>
                    <?php endif; ?>
                    <?php if (!empty($_ENV['COMPANY_PHONE']) && !empty($_ENV['COMPANY_EMAIL'])) : ?> | <?php endif; ?>
                    <?php if (!empty($_ENV['COMPANY_EMAIL'])) : ?>
                        Email: <?= $_ENV['COMPANY_EMAIL'] ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>

    </div>
    <footer class="text-center mt-5 mb-3 text-muted small">
        <hr>
        <p>Halaman ini dikembangkan oleh <strong>Gingin Abdul Goni</strong></p>
        <p>
            Bagian dari <a href="https://1112-project.com" target="_blank">1112 Project</a> &mdash;
            Sistem billing: <a href="https://member.mywifi.web.id" target="_blank">MyWiFi</a>
        </p>
        <p>
            Kunjungi repositori di
            <a href="https://github.com/ginginabdulgoni" target="_blank">GitHub (@ginginabdulgoni)</a>
        </p>
    </footer>


    <script>
        function cekTagihan() {
            const noServices = document.getElementById('no_services').value;
            if (!noServices) {
                alert('Harap isi No Layanan');
                return;
            }

            fetch(`api_unpaid.php?no_services=${encodeURIComponent(noServices)}`)
                .then(res => res.json())
                .then(res => {
                    const hasil = document.getElementById('hasil');
                    if (res.status && res.data && res.data.length > 0) {
                        const tagihan = res.data[0];
                        hasil.innerHTML = `
                        <div class="alert alert-info text-start">
                            <strong>${tagihan.name}</strong><br>
                            No Layanan: <strong>${tagihan.no_services}</strong><br>
                            Periode: ${tagihan.period}<br>
                            Jumlah Tagihan: <strong>Rp ${tagihan.amount.toLocaleString('id-ID')}</strong><br>
                            Jatuh Tempo: ${tagihan.due_date}<br>
                            Status: <span class="badge bg-danger">${tagihan.status}</span>
                        </div>
                    `;
                    } else {
                        const pesan = res.message || 'Tagihan tidak ditemukan atau sudah lunas.';
                        hasil.innerHTML = `<div class="alert alert-warning">${pesan}</div>`;
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal mengambil data dari server.');
                });
        }
    </script>
</body>

</html>