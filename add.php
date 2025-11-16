<?php
session_start();

// Pastikan array kontak tersedia
if (!isset($_SESSION['kontak'])) {
    $_SESSION['kontak'] = [];
}

// Cek login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

$errors = [];
$data = ['nama' => '', 'email' => '', 'telepon' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['nama'] = trim($_POST['nama'] ?? '');
    $data['email'] = trim($_POST['email'] ?? '');
    $data['telepon'] = trim($_POST['telepon'] ?? '');

    // Validasi Nama
    if ($data['nama'] === '') {
        $errors[] = "Nama harus diisi";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['nama'])) {
        $errors[] = "Nama hanya boleh huruf dan spasi";
    }

    // Validasi Email
    if ($data['email'] === '') {
        $errors[] = "Email harus diisi";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

    // Validasi Telepon
    if ($data['telepon'] === '') {
        $errors[] = "Nomor telepon harus diisi";
    } elseif (!preg_match("/^[0-9+\-\s()]+$/", $data['telepon'])) {
        $errors[] = "Format nomor telepon tidak valid";
    }

    // Jika aman → simpan
    if (empty($errors)) {
        $_SESSION['kontak'][] = $data;
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Kontak</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        body {
            background: #eef2f7;
        }

        .page-title {
            text-align: center;
            font-weight: 700;
            margin-bottom: 25px;
            color: #2b2f38;
        }

        .card-modern {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            border: none;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .form-control {
            border-radius: 10px;
            height: 45px;
        }

        .btn-main {
            background: #4b7bec;
            border: none;
            color: white;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: .2s;
        }
        .btn-main:hover {
            background: #3867d6;
        }

        .btn-back {
            background: #d1d6e0;
            border: none;
            color: #2d2f36;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: .2s;
        }
        .btn-back:hover {
            background: #b9bec8;
        }

        .btn-wrapper {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .error-box {
            border-left: 5px solid #e74c3c;
            padding: 15px;
            background: #fcebea;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <h2 class="page-title">Tambah Kontak Baru</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card-modern">

                <?php if (!empty($errors)): ?>
                    <div class="error-box mb-3">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control mb-3"
                           value="<?php echo htmlspecialchars($data['nama']); ?>">

                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control mb-3"
                           value="<?php echo htmlspecialchars($data['email']); ?>">

                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="telepon" class="form-control mb-3"
                           value="<?php echo htmlspecialchars($data['telepon']); ?>">

                    <div class="btn-wrapper">
                        <a href="login.php" class="btn btn-back">Kembali</a>
                        <button type="submit" class="btn btn-main">Simpan Kontak</button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

</body>
</html>
