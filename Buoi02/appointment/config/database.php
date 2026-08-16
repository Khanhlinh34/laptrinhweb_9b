<?php

$host = "localhost";
$dbname = "appointment_db";
$username = "root";
$password = "";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    echo "Kết nối database thành công!";

} catch (PDOException $e) {

    die("Lỗi kết nối: " . $e->getMessage());

}