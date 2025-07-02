<?php
header('Content-Type: application/json');


$licenses = json_decode(file_get_contents('licenses.json'), true);

$domain = $_GET['domain'] ?? '';
$key = $_GET['key'] ?? '';
$today = date('Y-m-d');


if (isset($licenses[$key])) {
    // Ambil detail lisensi
    $license = $licenses[$key];

    
    if (empty($license['domain'])) {
        // Auto bind domain
        $licenses[$key]['domain'] = $domain;

        
        file_put_contents('licenses.json', json_encode($licenses, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'valid', 'message' => 'Domain berhasil terdaftar.']);
    } else {
        // Kalau domainnya cocok
        if ($licenses[$key]['domain'] === $domain) {
            if ($today <= $license['expired']) {
                echo json_encode(['status' => 'valid', 'message' => 'Lisensi valid.']);
            } else {
                echo json_encode(['status' => 'expired', 'message' => 'Lisensi expired.']);
            }
        } else {
            echo json_encode(['status' => 'domain_mismatch', 'message' => 'Lisensi tidak cocok dengan domain ini.']);
        }
    }
} else {
    echo json_encode(['status' => 'invalid_key', 'message' => 'License key tidak ditemukan.']);
}
?>
