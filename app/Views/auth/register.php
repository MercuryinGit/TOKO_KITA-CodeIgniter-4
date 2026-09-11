<?= $this->include('layout/header') ?>

<div class="form-shell card overflow-hidden">
    <div class="row g-0">
        <div class="col-lg-5 auth-intro d-flex flex-column justify-content-between">
            <div><div class="eyebrow text-white-50 mb-3">Mulai dari sini</div><h1 class="display-6 fw-bold">Buat akun, pilih lebih mudah.</h1><p class="mt-3">Gabung untuk menemukan koleksi yang terasa lebih personal.</p></div>
            <div class="small text-white-50"><i class="bi bi-stars me-2"></i>Gratis dan cepat dibuat</div>
        </div>
        <div class="col-lg-7 auth-form">
                <h2 class="h3 mb-4">Buat akun baru</h2>

                <?= $this->include('partials/flash_messages') ?>

                <form action="<?= base_url('register/process') ?>" method="post">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" id="nama" name="nama" class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-person-plus me-2"></i>Daftar sekarang</button>
                </form>

                <p class="text-center mt-3 mb-0">
                    Sudah punya akun?
                    <a href="<?= base_url('login') ?>">Login</a>
                </p>
                </div>
        </div>
    </div>
</div>

<?= $this->include('layout/footer') ?>