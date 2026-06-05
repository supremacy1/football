<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->exec("CREATE DATABASE IF NOT EXISTS football_social_media CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "DB_CREATED";
} catch (Exception $e) {
    echo 'ERR:'.$e->getMessage();
}
