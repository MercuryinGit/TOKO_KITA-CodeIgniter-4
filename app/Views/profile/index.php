<?= $this->include('layout/header') ?>
<?php
$user = $user ?? [];
$products = $products ?? [];
$stats = $stats ?? [];
$isOwner = $isOwner ?? false;
$isFollowing = $isFollowing ?? false;
$displayName = (string) ($user['nama'] ?? 'user');
?>
<section class="profile-hero">
    <div class="profile-avatar"><i class="bi bi-person"></i></div>
    <div class="profile-identity">
        <div class="eyebrow">Profil pengguna</div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <h1 class="section-title mb-0"><?= esc($user['nama'] ?? '') ?></h1>
            <span class="online-status <?= !empty($user['last_seen_at']) && strtotime($user['last_seen_at']) >= time() - 300 ? 'is-online' : '' ?>">
                <span></span><?= !empty($user['last_seen_at']) && strtotime($user['last_seen_at']) >= time() - 300 ? 'Online' : 'Offline' ?>
            </span>
        </div>
        <p class="text-secondary mb-0">@<?= esc(strtolower((string) preg_replace('/[^a-zA-Z0-9]+/', '', $displayName))) ?></p>
    </div>
    <?php if (!$isOwner && session()->get('logged_in')): ?>
        <div class="profile-actions">
            <form action="<?= base_url('profil/' . $user['id'] . ($isFollowing ? '/unfollow' : '/follow')) ?>" method="post">
                <button type="submit" class="btn <?= $isFollowing ? 'btn-outline-primary' : 'btn-primary' ?>"><i class="bi bi-person-<?= $isFollowing ? 'check' : 'plus' ?> me-2"></i><?= $isFollowing ? 'Mengikuti' : 'Ikuti' ?></button>
            </form>
            <a href="<?= base_url('chat/' . $user['id']) ?>" class="btn btn-outline-dark"><i class="bi bi-chat-dots me-2"></i>Chat</a>
        </div>
    <?php endif; ?>
</section>
<?= $this->include('partials/flash_messages') ?>

<div class="row g-4">
    <div class="col-lg-4">
        <aside class="profile-sidebar">
            <div class="profile-bio">
                <div class="eyebrow mb-2">Tentang</div>
                <p class="mb-0"><?= esc($user['bio'] ?: 'User ini belum menulis bio.') ?></p>
                <?php if ($isOwner): ?>
                    <form action="<?= base_url('profil/bio') ?>" method="post" class="mt-3">
                        <label for="bio" class="form-label small">Tulis bio kamu</label>
                        <textarea id="bio" name="bio" class="form-control" rows="3" maxlength="500" placeholder="Ceritakan sedikit tentang lapak atau minatmu..."><?= esc($user['bio'] ?? '') ?></textarea>
                        <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Simpan bio</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="profile-stats">
                <div><strong><?= (int) ($stats['products'] ?? 0) ?></strong><span>Produk</span></div>
                <div><strong><?= (int) ($stats['followers'] ?? 0) ?></strong><span>Pengikut</span></div>
                <div><strong><?= (int) ($stats['following'] ?? 0) ?></strong><span>Diikuti</span></div>
            </div>
            <?php if ($isOwner): ?>
                <div class="profile-earnings">
                    <div class="eyebrow mb-2">Saldo penjualan</div>
                    <strong>Rp <?= number_format((int) ($user['saldo'] ?? 0), 0, ',', '.') ?></strong>
                    <small>Total pendapatan: Rp <?= number_format((int) ($salesTotal ?? 0), 0, ',', '.') ?></small>
                    <a href="<?= base_url('saldo') ?>" class="btn btn-primary btn-sm mt-3">Lihat mutasi saldo</a>
                </div>
                <a href="<?= base_url('seller') ?>" class="profile-link"><i class="bi bi-shop me-2"></i>Kelola lapak saya</a>
            <?php endif; ?>
        </aside>
    </div>
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div><div class="eyebrow">Etalase <?= $isOwner ? 'saya' : 'penjual' ?></div><h2 class="h3 mb-0">Produk yang dijual</h2></div>
            <span class="text-secondary small"><?= count($products) ?> produk</span>
        </div>
        <div class="row g-3">
            <?php foreach ($products as $product): ?>
                <div class="col-sm-6">
                    <article class="card product-card h-100">
                        <?php if (!empty($product['gambar'])): ?><img src="<?= base_url('assets/img/' . $product['gambar']) ?>" class="card-img-top" alt="<?= esc($product['nama_produk']) ?>"><?php endif; ?>
                        <div class="card-body p-3">
                            <div class="small text-uppercase text-secondary mb-1"><?= esc($product['nama_kategori'] ?? 'Produk') ?></div>
                            <h3 class="h5 product-name mb-2"><?= esc($product['nama_produk']) ?></h3>
                            <p class="text-secondary small mb-3"><?= esc($product['deskripsi'] ?? '') ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-auto"><strong class="price">Rp <?= number_format($product['harga'], 0, ',', '.') ?></strong><a href="<?= base_url('produk/detail/' . $product['id_produk']) ?>" class="btn btn-sm btn-primary">Lihat</a></div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (empty($products)): ?><div class="empty-state"><i class="bi bi-box-seam"></i><p>Belum ada produk yang dijual.</p></div><?php endif; ?>
    </div>
</div>
<?= $this->include('layout/footer') ?>
