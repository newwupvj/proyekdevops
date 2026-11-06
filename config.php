<?php

function get_database_connection(): mysqli
{
    $host = getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: 'db';
    $user = getenv('MYSQLUSER') ?: getenv('DB_USER') ?: '';
    $pass = getenv('MYSQLPASSWORD') ?: getenv('DB_PASS') ?: '';
    $name = getenv('MYSQLDATABASE') ?: getenv('DB_NAME') ?: '';
    $port = (int) (getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306);

    if ($user === '' || $name === '') {
        http_response_code(500);
        exit('Database belum dikonfigurasi.');
    }

    mysqli_report(MYSQLI_REPORT_OFF);
    $connection = new mysqli($host, $user, $pass, $name, $port);

    if ($connection->connect_errno) {
        http_response_code(503);
        exit('Database tidak tersedia.');
    }

    $connection->set_charset('utf8mb4');
    return $connection;
}
