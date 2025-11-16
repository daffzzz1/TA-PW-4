<?php
// Fixed and cleaned up PHP + HTML structure
// Your corrected edit.php code goes here...

session_start();

// Cek login
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php");
    exit();
}

// Ambil ID kontak
$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;

if ($id < 0 || !isset($_SESSION['kontak'][$id])) {
    echo "<p>Data tidak ditemukan.</p>";
    exit();
}

// Ambil data awal
$data = $_SESSION['kontak'][$id];
$errors = [];

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validasi Nama
    if (empty($_POST['nama'])) {
        $errors[] = "Nama harus diisi";
    } else {
        $data['nama'] = trim($_POST['nama']);
        if (!preg_match("/^[a-zA-Z\s]+$/", $data['nama'])) {
            $errors[] = "Nama hanya boleh mengandung huruf dan spasi";
        }
    }

    // Validasi Email
    if (empty($_POST['email'])) {
        $errors[] = "Email harus diisi";
    } else {
        $data['email'] = trim($_POST['email']);
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }
    }

    // Validasi Telepon
    if (empty($_POST['telepon'])) {
        $errors[] = "Nomor telepon harus diisi";
    } else {
        $data['telepon'] = trim($_POST['telepon']);
        if (!preg_match("/^[0-9+\-\s()]+$/", $data['telepon'])) {
            $errors[] = "Format nomor telepon tidak valid";
        }
    }

    // Jika tidak ada error → update & kembali
    if (empty($errors)) {
        $_SESSION['kontak'][$id] = $data;
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
    <title>Edit Kontak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f5f7fa;
        }
        .card-custom {
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .btn-primary {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-secondary {
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow p-4 rounded-4 border-0" style="max-width: 650px; margin: auto; background: #ffffff;">

                <h2 class="text-center fw-bold mb-4">Edit Kontak</h2>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control rounded-3" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control rounded-3" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="telepon" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control rounded-3" id="telepon" name="telepon" value="<?php echo htmlspecialchars($data['telepon']); ?>">
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="login.php" class="btn btn-primary px-4 py-2 rounded-3">Kembali</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-3">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
</body>
</html>