<?php
// db.php
$host = "localhost";
$dbname = "db_web_p14";
$username = "root";
$password = ""; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Koneksi gagal: " . $e->getMessage()); 
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Sistem gagal terhubung ke database. Silakan coba beberapa saat lagi."
    ]);
    exit;
}
?>