<?php
    session_start();
    if (!isset($_SESSION['user_id'])) 
    {
        header("Location: index.html");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        body {
            background-color: #f0f4f8;
            color: #1e293b;
        }
        .navbar {
            background-color: #1e3a8a;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .btn-logout {
            background-color: #dc2626;
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-logout:hover {
            background-color: #b91c1c;
        }
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        /* Navigasi Menu Dashboard */
        .dashboard-nav {
            display: flex;
            background-color: white;
            padding: 0.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            gap: 0.5rem;
        }
        .nav-btn {
            flex: 1;
            background: none;
            border: none;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        .nav-btn:hover {
            background-color: #f1f5f9;
            color: #1e3a8a;
        }
        .nav-btn.active {
            background-color: #3b82f6;
            color: white;
        }
        .nav-btn.btn-danger-tab:hover {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .nav-btn.btn-danger-tab.active {
            background-color: #dc2626;
            color: white;
        }
        /* Card Style */
        .card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 1rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }
        .profile-info {
            margin-top: 1rem;
        }
        .profile-item {
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }
        .profile-item span {
            color: #64748b;
            display: inline-block;
            width: 140px;
        }
        .profile-item strong {
            color: #0f172a;
        }
        /* Form Controls */
        .mb-3 {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            color: #334155;
        }
        .form-control {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.375rem;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background-color: #1d4ed8;
        }
        .btn-danger {
            background-color: #dc2626;
        }
        .btn-danger:hover {
            background-color: #b91c1c;
        }
        /* Alerts */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .alert-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .alert-danger {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .d-none {
            display: none !important;
        }
        .text-muted {
            color: #64748b;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <span class="navbar-brand">Secure Management Dashboard</span>
        <a href="logout.php" class="btn-logout">Log Out</a>
    </nav>

    <div class="container">
        <div class="dashboard-nav">
            <button class="nav-btn active" id="tab-detail" onclick="switchTab('detail')">Detail Profil</button>
            <button class="nav-btn" id="tab-update" onclick="switchTab('update')">Ubah Data Profil</button>
            <button class="nav-btn btn-danger-tab" id="tab-delete" onclick="switchTab('delete')">Hapus Profil</button>
        </div>

        <div id="section-detail" class="card">
            <h5 class="card-title">Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h5>
            <div class="profile-info">
                <div class="profile-item"><span>ID Pengguna:</span> <strong><?php echo $_SESSION['user_id']; ?></strong></div>
                <div class="profile-item"><span>Email Aktif:</span> <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong></div>
                <div class="profile-item"><span>Terdaftar Sejak:</span> <strong><?php echo $_SESSION['created_at']; ?></strong></div>
            </div>
        </div>

        <div id="section-update" class="card d-none">
            <h5 class="card-title">Perbarui Profil Mandiri</h5>
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
                <button type="submit" class="btn-submit" id="updateBtn">Simpan Pembaruan</button>
            </form>
        </div>

        <div id="section-delete" class="card d-none">
            <h5 class="card-title" style="color: #dc2626; border-bottom-color: #fee2e2;">Zona Bahaya</h5>
            <div id="deleteAlert" class="alert d-none"></div>
            <p class="text-muted" style="margin-bottom: 1rem;">Akun Anda akan dihapus secara permanen dari basis data sistem.</p>
            <form id="formDelete">
                <div class="mb-3">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Masukkan password konfirmasi" required>
                </div>
                <button type="submit" class="btn-submit btn-danger" id="deleteBtn">Hapus Akun Saya</button>
            </form>
        </div>
    </div>

    <script>
        // Fungsi Navigasi Tab Menu
        function switchTab(section) {
            // Sembunyikan semua section
            document.getElementById('section-detail').classList.add('d-none');
            document.getElementById('section-update').classList.add('d-none');
            document.getElementById('section-delete').classList.add('d-none');
            
            // Nonaktifkan status aktif tombol menu
            document.getElementById('tab-detail').classList.remove('active');
            document.getElementById('tab-update').classList.remove('active');
            document.getElementById('tab-delete').classList.remove('active');

            // Tampilkan section terpilih & set tombol aktif
            document.getElementById('section-' + section).classList.remove('d-none');
            document.getElementById('tab-' + section).classList.add('active');
        }

        // Proses Update Profil (AJAX/Fetch)
        document.getElementById('formUpdate').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('updateBtn');
            const alertBox = document.getElementById('updateAlert');
            btn.disabled = true;
            btn.textContent = "Menyimpan...";
            
            try {
                const response = await fetch('api/profile_process.php', { method: 'POST', body: new FormData(e.target) });
                const data = await response.json();
                alertBox.className = `alert ${response.ok ? 'alert-success' : 'alert-danger'}`;
                alertBox.textContent = data.message;
                alertBox.classList.remove('d-none');
                if(response.ok) setTimeout(() => location.reload(), 1000);
            } catch (err) { console.error(err); }
            finally { 
                btn.disabled = false; 
                btn.textContent = "Simpan Pembaruan";
            }
        });

        // Proses Delete Akun (AJAX/Fetch)
        document.getElementById('formDelete').addEventListener('submit', async function (e) {
            e.preventDefault();
            if(!confirm("Apakah Anda yakin ingin menghapus akun ini secara permanen?")) return;
            const btn = document.getElementById('deleteBtn');
            const alertBox = document.getElementById('deleteAlert');
            btn.disabled = true;
            btn.textContent = "Menghapus Akun...";

            try {
                const response = await fetch('api/delete_process.php', { method: 'POST', body: new FormData(e.target) });
                const data = await response.json();
                alertBox.className = `alert ${response.ok ? 'alert-success' : 'alert-danger'}`;
                alertBox.textContent = data.message;
                alertBox.classList.remove('d-none');
                if(response.ok) setTimeout(() => window.location.href = 'index.html', 1500);
            } catch (err) { console.error(err); }
            finally { 
                btn.disabled = false; 
                btn.textContent = "Hapus Akun Saya";
            }
        });
    </script>
</body>
</html>