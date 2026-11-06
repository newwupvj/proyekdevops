<?php
require __DIR__ . '/config.php';

$connection = get_database_connection();
$result = $connection->query('SELECT 1');
$connection->close();

if ($result === false) {
    http_response_code(503);
    exit('not ready');
}

header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);
