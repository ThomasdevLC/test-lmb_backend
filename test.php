<?php
require_once 'api/ClientAPI.php';

$token = getAuthToken();

if (!$token) {
    die('Erreur d\'authentification');
}

$headersWithToken = array_merge(API_HEADERS, [
    'Authorization: Basic ' . base64_encode(':' . $token)
]);


$response = callApi('GET', 'clients', null, $headersWithToken);

echo "<pre>";
print_r($response);
echo "</pre>";
