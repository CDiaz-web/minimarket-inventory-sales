<?php
// $db = mysqli_connect(
//     $_ENV['DB_HOST'] ?? '',
//     $_ENV['DB_USER'] ?? '', 
//     $_ENV['DB_PASS'] ?? '', 
//     $_ENV['DB_NAME'] ?? ''
// );

// if (!$db) {
//     echo "Error: No se pudo conectar a MySQL.";
//     echo "errno de depuración: " . mysqli_connect_errno();
//     echo "error de depuración: " . mysqli_connect_error();
//     exit;
// }



function env_var($key, $default = '') {
    return $_ENV[$key] ?? getenv($key) ?? $default;
}

$db = mysqli_connect(
    env_var('DB_HOST'),
    env_var('DB_USER'),
    env_var('DB_PASS'),
    env_var('DB_NAME')
);

if (!$db) {
    echo "Error: No se pudo conectar a MySQL.";
    echo " errno: " . mysqli_connect_errno();
    echo " error: " . mysqli_connect_error();
    exit;
}