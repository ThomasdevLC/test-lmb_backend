<?php
/**
 *
 * Updates a client's information by ID.
 * Accepts only allowed fields. $allowedFields
 */

require_once __DIR__ . '/../api/ClientAPI.php';

header('Content-Type: application/json');

// 1. Validate ID
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing "id" parameter']);
    exit;
}

$clientId = $_GET['id'];

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON ']);
    exit;
}

$allowedFields = ['nom', 'email', 'tel', 'adresse', 'code_postal', 'ville'];
$input = array_intersect_key($input, array_flip($allowedFields));

if (empty($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'ce champ n\'est pas valide']);
    exit;
}

// 4. Authenticate
$token = getAuthToken();
if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'erreur d\'authentification']);
    exit;
}

// 5. Prepare headers
$headersWithToken = array_merge(API_HEADERS, [
    'Authorization: Basic ' . base64_encode(':' . $token)
]);

// 6. Make PUT request to /clients/{id}
$response = callApi('PUT', "clients/{$clientId}", $input, $headersWithToken);

// 7. Return API response
http_response_code($response['http_code']);
echo json_encode($response['body']);
