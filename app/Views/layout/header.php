<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Toko Kita' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=20260912') ?>">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= base_url('/') ?>">
            TOKO KITA
        </a>
        <button class="navbar-toggler" type="button"                 data-bs-toggle="collapse" data-bs-target="#navbarNav">             <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav align-items-lg-center gap-lg-2 ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= base_url('produk') ?>"><i class="bi bi-grid me-1"></i>Produk</a></li>
                <?php if (session()->get('role') === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin') ?>"><i class="bi bi-sliders me-1"></i>Admin</a></li>
                <?php endif; ?>
                <?php if (session()->get('logged_in')): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('saldo') ?>"><i class="bi bi-wallet2 me-1"></i>Saldo</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('seller') ?>"><i class="bi bi-shop me-1"></i>Lapak Saya</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('chat') ?>"><i class="bi bi-chat-dots me-1"></i>Pesan</a></li>
                    <li class="nav-item">
                        <a class="nav-link account-chip text-white" href="<?= base_url('profil') ?>"><i class="bi bi-person-circle me-1"></i><?= esc(session()->get('nama')) ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-1"></i>Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login') ?>"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('register') ?>">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4 flex-grow-1">
