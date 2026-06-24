<?php
// register_process.php
header("Content-Type: application/json; charset=UTF-8");
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Metode request tidak didukung."]);
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// 1. Validasi Wajib Diisi
if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Semua form wajib diisi."]);
    exit;
}

// 2. Validasi Format Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Format penulisan email tidak valid."]);
    exit;
}

// 3. Validasi Kekuatan Password (Min 6 Karakter, Huruf Kapital, Angka, Karakter Spesial)
if (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9]/', $password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Password minimal 6 karakter, mengandung huruf kapital, angka, dan karakter spesial."]);
    exit;
}

try {
    // 4. Cek Keunikan Akun
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
    $stmtCheck->execute([':username' => $username, ':email' => $email]);
    
    if ($stmtCheck->fetchColumn() > 0) {
        http_response_code(409);
        echo json_encode(["status" => "error", "message" => "Username atau Email sudah terdaftar."]);
        exit;
    }

    // 5. Hashing Password Bcrypt
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 6. Insert Data
    $stmtInsert = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmtInsert->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashedPassword
    ]);

    http_response_code(201);
    echo json_encode(["status" => "success", "message" => "Proses registrasi akun baru berhasil diselesaikan!"]);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Kegagalan sistem internal saat menyimpan data."]);
}
?>