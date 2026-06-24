<?php
// login_process.php
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan."]);
    exit;
}

$identity = isset($_POST['identity']) ? trim($_POST['identity']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($identity) || empty($password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Form login wajib diisi."]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :identity OR email = :identity");
    $stmt->execute([':identity' => $identity]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['created_at'] = $user['created_at'];

        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Login berhasil! Mengalihkan halaman..."]);
    } else {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Username/Email atau Password Anda salah."]);
    }
} catch (PDOException $e) {
    error_log("Login Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan internal server."]);
}
?>