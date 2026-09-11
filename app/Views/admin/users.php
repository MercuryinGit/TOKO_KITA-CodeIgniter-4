<?= $this->include('layout/header') ?>

<div class="admin-toolbar">
    <div>
        <div class="eyebrow">Ruang admin</div>
        <h1 class="section-title mb-1">Kelola pengguna</h1>
        <p class="text-secondary mb-0">Lihat akun terdaftar dan atur akses administrator.</p>
    </div>
    <a href="<?= base_url('admin') ?>" class="btn btn-light"><i class="bi bi-arrow-left me-2"></i>Kembali ke produk</a>
</div>

<div class="mb-4">
    <?= $this->include('partials/flash_messages') ?>
</div>

<div class="card table-card">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (($users ?? []) as $number => $user): ?>
                <?php $isCurrentUser = (int) $user['id'] === (int) session()->get('user_id'); ?>
                <tr>
                    <td><?= $number + 1 ?></td>
                    <td>
                        <strong><?= esc($user['nama']) ?></strong>
                        <?php if ($isCurrentUser): ?><div class="small text-secondary">Akun kamu</div><?php endif; ?>
                    </td>
                    <td><?= esc($user['email']) ?></td>
                    <td><span class="badge <?= ($user['role'] ?? 'user') === 'admin' ? 'text-bg-primary' : 'text-bg-light' ?>"><?= esc($user['role'] ?? 'user') ?></span></td>
                    <td><span class="badge <?= !empty($user['is_verified']) ? 'text-bg-success' : 'text-bg-warning' ?>"><?= !empty($user['is_verified']) ? 'Terverifikasi' : 'Belum diverifikasi' ?></span></td>
                    <td>
                        <?php if ($isCurrentUser): ?>
                            <span class="small text-secondary">Tidak dapat mengubah diri sendiri</span>
                        <?php else: ?>
                            <form action="<?= base_url('admin/users/access/' . $user['id']) ?>" method="post" class="d-flex flex-wrap gap-2">
                                <select name="role" class="form-select form-select-sm" aria-label="Role <?= esc($user['nama']) ?>" style="max-width: 130px;">
                                    <option value="user" <?= ($user['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= ($user['role'] ?? 'user') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <select name="is_verified" class="form-select form-select-sm" aria-label="Status <?= esc($user['nama']) ?>" style="max-width: 160px;">
                                    <option value="1" <?= !empty($user['is_verified']) ? 'selected' : '' ?>>Verified</option>
                                    <option value="0" <?= empty($user['is_verified']) ? 'selected' : '' ?>>Belum verified</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->include('layout/footer') ?>