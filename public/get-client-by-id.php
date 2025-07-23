<?php
require_once __DIR__ . '/../api/ClientAPI.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'id manquant']);
    exit;
}

$clientId = $_GET['id'];

$token = getAuthToken();
if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Échec authentification']);
    exit;
}

$headersWithToken = array_merge(API_HEADERS, [
    'Authorization: Basic ' . base64_encode(':' . $token)
]);

$filters = [
    'fields' => 'id,nom,email,tel,adresse,code postal,ville'
];


$response = callApi('GET', "clients/{$clientId}", $filters, $headersWithToken);

http_response_code($response['http_code']);
echo json_encode($response['body']);
