<?= $this->include('layout/header') ?>
<?php $user = $user ?? []; $mutations = $mutations ?? []; ?>
<div class="page-heading">
    <div class="eyebrow">Keuangan akun</div>
    <h1 class="section-title mb-1">Saldo Saya</h1>
    <p class="text-secondary mb-0">Gunakan saldo untuk membeli dan menerima hasil penjualan produkmu.</p>
</div>
<?= $this->include('partials/flash_messages') ?>
<div class="row g-4 mb-5">
    <div class="col-lg-5">
        <div class="card p-4 h-100">
            <div class="eyebrow mb-2">Saldo tersedia</div>
            <div class="display-6 fw-bold mb-4">Rp <?= number_format((int) ($user['saldo'] ?? 0), 0, ',', '.') ?></div>
            <form action="<?= base_url('saldo/topup') ?>" method="post">
                <label for="jumlah" class="form-label">Tambah saldo</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input id="jumlah" name="jumlah" type="number" min="10000" step="1000" class="form-control" placeholder="10000" required>
                    <button class="btn btn-primary" type="submit">Top up</button>
                </div>
                <small class="text-secondary">Minimal Rp 10.000.</small>
            </form>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card p-4 h-100">
            <h2 class="h5 mb-3">Riwayat saldo</h2>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Waktu</th><th>Keterangan</th><th class="text-end">Jumlah</th></tr></thead>
                    <tbody>
                    <?php foreach ($mutations as $mutation): ?>
                        <tr>
                            <td><?= esc($mutation['created_at']) ?></td>
                            <td><?= esc($mutation['keterangan']) ?></td>
                            <td class="text-end <?= (int) $mutation['jumlah'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= (int) $mutation['jumlah'] >= 0 ? '+' : '' ?>Rp <?= number_format(abs((int) $mutation['jumlah']), 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($mutations)): ?><tr><td colspan="3" class="text-center text-secondary">Belum ada mutasi saldo.</td></tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout/footer') ?>