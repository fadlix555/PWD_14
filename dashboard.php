<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">Secure Management Dashboard</span>
            <a href="logout.php" class="btn btn-danger btn-sm">Log Out</a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-3">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h5>
                        <hr>
                        <p class="text-start mb-1 text-muted">ID Pengguna: <b class="text-dark"><?php echo $_SESSION['user_id']; ?></b></p>
                        <p class="text-start mb-1 text-muted">Email Aktif: <b class="text-dark"><?php echo htmlspecialchars($_SESSION['email']); ?></b></p>
                        <p class="text-start mb-1 text-muted">Terdaftar Sejak: <b class="text-dark"><?php echo $_SESSION['created_at']; ?></b></p>
                    </div>
                </div>
            </div>

            <div class="col-md-5 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white fw-bold py-3">Perbarui Profil Mandiri</div>
                    <div class="card-body p-4">
                        <div id="updateAlert" class="alert d-none"></div>
                        <form id="formUpdate">
                            <div class="mb-3">
                                <label class="form-label">Alamat Email Baru</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Baru (Kosongkan jika tidak diganti)</label>
                                <input type="password" class="form-control" name="new_password">
                            </div>
                            <button type="submit" class="btn btn-warning w-100 fw-semibold" id="updateBtn">Simpan Pembaruan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm rounded-3 border-start border-danger border-3">
                    <div class="card-header bg-white fw-bold text-danger py-3">Zona Bahaya</div>
                    <div class="card-body p-4">
                        <div id="deleteAlert" class="alert d-none"></div>
                        <p class="small text-muted">Akun akan dihapus permanen dari basis data.</p>
                        <form id="formDelete">
                            <div class="mb-3">
                                <input type="password" class="form-control form-control-sm" name="confirm_password" placeholder="Masukkan password konfirmasi" required>
                            </div>
                            <button type="submit" class="btn btn-danger btn-sm w-100 fw-bold" id="deleteBtn">Hapus Akun Saya</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Proses Update Profil (AJAX/Fetch)
        document.getElementById('formUpdate').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('updateBtn');
            const alertBox = document.getElementById('updateAlert');
            btn.disabled = true;
            
            try {
                const response = await fetch('profile_process.php', { method: 'POST', body: new FormData(e.target) });
                const data = await response.json();
                alertBox.className = `alert ${response.ok ? 'alert-success' : 'alert-danger'}`;
                alertBox.textContent = data.message;
                alertBox.classList.remove('d-none');
                if(response.ok) setTimeout(() => location.reload(), 1000);
            } catch (err) { console.error(err); }
            finally { btn.disabled = false; }
        });

        // Proses Delete Akun (AJAX/Fetch)
        document.getElementById('formDelete').addEventListener('submit', async function (e) {
            e.preventDefault();
            if(!confirm("Apakah Anda yakin ingin menghapus akun ini secara permanen?")) return;
            const btn = document.getElementById('deleteBtn');
            const alertBox = document.getElementById('deleteAlert');
            btn.disabled = true;

            try {
                const response = await fetch('delete_process.php', { method: 'POST', body: new FormData(e.target) });
                const data = await response.json();
                alertBox.className = `alert ${response.ok ? 'alert-success' : 'alert-danger'} small`;
                alertBox.textContent = data.message;
                alertBox.classList.remove('d-none');
                if(response.ok) setTimeout(() => window.location.href = 'login.html', 1500);
            } catch (err) { console.error(err); }
            finally { btn.disabled = false; }
        });
    </script>
</body>
</html>