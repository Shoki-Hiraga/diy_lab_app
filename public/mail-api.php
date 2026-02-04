<?php
header('Content-Type: application/json; charset=UTF-8');

// POST以外は拒否
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// 簡易APIキー（あとで.envに移す）
$API_KEY = 'your-secret-key';

if (!isset($_POST['api_key']) || $_POST['api_key'] !== $API_KEY) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// 必須パラメータ
$to      = $_POST['to'] ?? '';
$subject = $_POST['subject'] ?? '';
$body    = $_POST['body'] ?? '';

if ($to === '' || $subject === '' || $body === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

// 日本語メール設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// 送信元
$headers = "From: DIY LAB <info@diy-lab.net>";

// メール送信
$result = mb_send_mail($to, $subject, $body, $headers);

if ($result) {
    echo json_encode(['status' => 'ok']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Send failed']);
}
