<?php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Ambil dari .env
$apiKey  = $_ENV['API_KEY'] ?? '';
$baseUrl = $_ENV['API_BASE_URL'] ?? '';

header('Content-Type: application/json');

// Ambil parameter
$noServices = $_GET['no_services'] ?? '';

if (!$noServices) {
    echo json_encode([
        'status' => false,
        'message' => 'no_services is required',
    ]);
    exit;
}

// Akses API tagihan
$url = $baseUrl . '/api/bill/unpaid?no_services=' . urlencode($noServices);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-MYWIFI-KEY: ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
// Jika berhasil, langsung kirim response dari API pusat
echo $response;
