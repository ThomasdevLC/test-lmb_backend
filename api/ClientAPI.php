<?php

require_once __DIR__ . '/../config.php';

function callApi($method, $endpoint, $data = null, $customHeaders = null): array
{
    $url = API_BASE_URL . $endpoint;

    if (strtoupper($method) === 'GET' && !empty($data)) {
        $url .= '?' . http_build_query($data);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

    $headers = $customHeaders ?? API_HEADERS;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($data !== null && strtoupper($method) !== 'GET') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return ['http_code' => 500, 'body' => ['message' => curl_error($ch)]];
    }

    curl_close($ch);

    return [
        'http_code' => $httpCode,
        'body'      => json_decode($response, true)
    ];
}

function getAuthToken()
{
    $url = API_BASE_URL . 'auth';

    $data = [
        'username' => 'test_api',
        'password' => 'api123456',
        'password_type' => 0,
        'code_application' => 'webservice_externe',
        'code_version' => '1'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/api.rest-v1+json',
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return null;
    }

    curl_close($ch);

    $result = json_decode($response, true);

    if ($httpCode === 200 && isset($result['datas']['token'])) {
        return $result['datas']['token'];
    }

    return null;
}
