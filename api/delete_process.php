<?php
// delete_process.php
header("Content-Type: application/json; charset=UTF-8");
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Akses ditolak."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

try {
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch();

    if ($user && password_verify($confirm_password, $user['password'])) {
        $stmtDelete = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmtDelete->execute([':id' => $user_id]);
        
        session_destroy(); // Hancurkan sesi setelah dihapus
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Akun Anda telah dihapus secara permanen."]);
    } else {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Konfirmasi password salah."]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Terjadi kesalahan internal server."]);
}
?>