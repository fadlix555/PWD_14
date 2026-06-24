<?php
// profile_process.php
header("Content-Type: application/json; charset=UTF-8");
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Akses tidak sah."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

if (empty($email)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Email wajib diisi."]);
    exit;
}

try {
    if (!empty($new_password)) {
        if (strlen($new_password) < 6 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Password baru tidak memenuhi standar kekuatan password."]);
            exit;
        }
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET email = :email, password = :password WHERE id = :id");
        $stmt->execute([':email' => $email, ':password' => $hashedPassword, ':id' => $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET email = :email WHERE id = :id");
        $stmt->execute([':email' => $email, ':id' => $user_id]);
    }

    $_SESSION['email'] = $email; // Update data session
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Profil berhasil diperbarui!"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui database."]);
}
?>