<?php

require_once __DIR__ . '/api/ClientAPI.php';

$token = getAuthToken();

echo '<pre>';
var_dump($token);
echo '</pre>';
exit;
