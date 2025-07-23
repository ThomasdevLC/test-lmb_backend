<?php
require_once __DIR__ . '/../api/cors.php';
require_once __DIR__ . '/../api/ClientAPI.php';


header('Content-Type: application/json');

$token = getAuthToken();
if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Échec authentification']);
    exit;
}

$headersWithToken = array_merge(API_HEADERS, [
    'Authorization: Basic ' . base64_encode(':' . $token)
]);

$filters = array_filter([
    'nom'    => $_GET['nom']    ?? null,
    'ville'  => $_GET['ville']  ?? null,
    'sort'   => $_GET['sort']   ?? null,
    'fields' => $_GET['fields'] ?? null,
    'limit'  => $_GET['limit']  ?? null
]);

$response = callApi('GET', 'clients', $filters, $headersWithToken);

http_response_code($response['http_code']);
echo json_encode($response['body']);
