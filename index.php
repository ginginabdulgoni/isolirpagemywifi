<?php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$companyName    = $_ENV['COMPANY_NAME'] ?? 'Perusahaan Anda';
$companyAddress = $_ENV['COMPANY_ADDRESS'] ?? '';
$companyPhone   = $_ENV['COMPANY_PHONE'] ?? '';
$companyEmail   = $_ENV['COMPANY_EMAIL'] ?? '';
$apiKey         = $_ENV['API_KEY'] ?? '';
$baseUrl        = $_ENV['API_BASE_URL'] ?? '';

if (isset($_GET['action']) && $_GET['action'] === 'cek_tagihan') {
    header('Content-Type: application/json');

    $noServices = $_GET['no_services'] ?? '';
    if (!$noServices) {
        echo json_encode([
            'status' => false,
            'message' => 'no_services is required',
        ]);
        exit;
    }

    $url = $baseUrl . '/api/bill/unpaid?no_services=' . urlencode($noServices);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-MYWIFI-KEY: ' . $apiKey
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Internet Diisolir | <?= $companyName ?></title>
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
        <img src="expired.png" alt="Isolir" class="wifi-icon mb-3">
        <div class="notice-header">MOHON MAAF</div>
        <div class="notice-sub">Layanan Internet Anda Saat Ini Diisolir</div>

    <div class="box-alert mt-4 text-start mx-auto" style="max-width: 600px;">
    <p><strong>Pelanggan Internet yang Terhormat,</strong></p>
    <p>Layanan internet Anda <strong>diisolir</strong> oleh sistem kami karena keterlambatan pembayaran.</p>
    <p>Segera lakukan <strong>pembayaran</strong> untuk mengaktifkan kembali layanan.</p>
    <p>Butuh bantuan? Hubungi kami di bawah ini.</p>
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
    <?php if ($companyName): ?><p><strong><?= $companyName ?></strong></p><?php endif; ?>
    <?php if ($companyAddress): ?><p><?= $companyAddress ?></p><?php endif; ?>

    <?php if ($companyPhone || $companyEmail): ?>
        <p>
            <?php if ($companyPhone): ?>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $companyPhone) ?>" target="_blank">
                    Telp: <?= $companyPhone ?>
                </a>
            <?php endif; ?>
            <?php if ($companyPhone && $companyEmail): ?> | <?php endif; ?>
            <?php if ($companyEmail): ?>
                Email: <?= $companyEmail ?>
            <?php endif; ?>
        </p>
    <?php endif; ?>
</div>

    </div>

    <footer class="text-center mt-5 mb-3 text-muted small">
        <hr>
        <p>Halaman ini dikembangkan oleh <strong>Gingin Abdul Goni</strong></p>
        <p>Bagian dari <a href="https://1112-project.com" target="_blank">1112 Project</a> — Sistem billing: <a href="https://member.mywifi.web.id" target="_blank">MyWiFi</a></p>
        <p>Kunjungi repositori di <a href="https://github.com/ginginabdulgoni" target="_blank">GitHub (@ginginabdulgoni)</a></p>
    </footer>

    <script>
        const BASE_URL = "<?= $baseUrl ?>";

        function cekTagihan() {
            const noServices = document.getElementById('no_services').value.trim();
            if (!noServices) {
                alert('Harap isi No Layanan');
                return;
            }

            fetch(`?action=cek_tagihan&no_services=${encodeURIComponent(noServices)}`)
                .then(res => res.json())
                .then(res => {
                    const hasil = document.getElementById('hasil');
                    if (res.status && res.data && res.data.length > 0) {
                        const tagihan = res.data[0];
                        const checkoutUrl = `${BASE_URL}/checkout/payment?invoice=${tagihan.invoice}&noservices=${tagihan.no_services}`;
                        hasil.innerHTML = `
                            <div class="alert alert-info text-start">
                                <strong>${tagihan.name}</strong><br>
                                No Layanan: <strong>${tagihan.no_services}</strong><br>
                                Invoice: <strong>${tagihan.invoice}</strong><br>
                                Periode: ${tagihan.period}<br>
                                Jumlah Tagihan: <strong>Rp ${parseInt(tagihan.amount).toLocaleString('id-ID')}</strong><br>
                                Jatuh Tempo: ${tagihan.due_date}<br>
                                Status: <span class="badge bg-danger">${tagihan.status}</span><br><br>
                                <a href="${checkoutUrl}" class="btn btn-success" target="_blank">Bayar Sekarang</a>
                            </div>
                        `;
                    } else {
                        hasil.innerHTML = `<div class="alert alert-warning">${res.message || 'Tagihan tidak ditemukan atau sudah lunas.'}</div>`;
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
