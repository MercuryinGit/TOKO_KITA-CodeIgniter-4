<?= $this->include('layout/header') ?>

<div class="form-shell card overflow-hidden">
    <div class="row g-0">
        <div class="col-lg-5 auth-intro d-flex flex-column justify-content-between">
            <div><div class="eyebrow text-white-50 mb-3">Selamat datang kembali</div><h1 class="display-6 fw-bold">Masuk ke ruang belanjamu.</h1><p class="mt-3">Simpan pilihanmu dan nikmati pengalaman belanja yang lebih rapi.</p></div>
            <div class="small text-white-50"><i class="bi bi-shield-check me-2"></i>Session aman untuk akunmu</div>
        </div>
        <div class="col-lg-7 auth-form">
                <h2 class="h3 mb-4">Login ke akun</h2>

                <?= $this->include('partials/flash_messages') ?>

                <form action="<?= base_url('login/process') ?>" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-arrow-right me-2"></i>Masuk</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Belum punya akun?
                    <a href="<?= base_url('register') ?>">Register</a>
                </p>
                </div>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>