<?php
session_start();


$timeout_duration = 1800;

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > $timeout_duration) {
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
        }
    }
    $_SESSION['last_activity'] = time();
}


if (!isset($_SESSION['kontak'])) {
    $_SESSION['kontak'] = [];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == "admin" && $password == "admin") {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['last_activity'] = time();
    } else {
        $login_error = "Username atau password salah!";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ContactBase</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .header-box {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.15);
        }

        .card-custom {
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0px 3px 8px rgba(0,0,0,0.12);
            background: white;
        }

        .table thead {
            background: #0d6efd;
            color: white;
        }

        .btn-custom {
            border-radius: 30px;
            padding: 6px 15px;
        }
    </style>
</head>

<body>

<div class="container py-4">

    <div class="header-box">
        <h2 class="fw-bold">📇 ContactBase</h2>
        <p class="mb-0">Smart application to manage contact list</p>
    </div>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Halo, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> 👋</h5>
            <a href="login.php?action=logout" class="btn btn-danger btn-sm btn-custom">
                Logout
            </a>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-semibold">Daftar Kontak</h4>
            <a href="add.php" class="btn btn-success btn-custom">+ Tambah Kontak Baru</a>
        </div>

        <div class="card card-custom">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($_SESSION['kontak'])): ?>
                        <tr>
                            <td colspan="4" class="text-center py-3">Belum ada data kontak.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($_SESSION['kontak'] as $index => $kontak): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($kontak['nama']); ?></td>
                                <td><?php echo htmlspecialchars($kontak['email']); ?></td>
                                <td><?php echo htmlspecialchars($kontak['telepon']); ?></td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?php echo $index; ?>"
                                       class="btn btn-warning btn-sm btn-custom">Edit</a>

                                    <a href="delete.php?id=<?php echo $index; ?>"
                                       class="btn btn-danger btn-sm btn-custom"
                                       onclick="return confirm('Yakin ingin menghapus kontak ini?')">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php else: ?>

        <div class="mx-auto" style="max-width: 420px;">
            <div class="card card-custom">
                <h4 class="text-center mb-3">Login</h4>

                <?php if (isset($login_error)): ?>
                    <div class="alert alert-danger"> <?php echo $login_error; ?> </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" value="admin" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" value="admin" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary w-100 btn-custom">
                        Login
                    </button>
                </form>

                <p class="text-muted mt-3 text-center small">Hint:admin</p>
            </div>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
