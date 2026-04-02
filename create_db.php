<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1', 'root', '0183304fh');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS school_management');
    echo 'Database created successfully';
} catch(PDOException $e) {
    echo $e->getMessage();
}
