<?= $this->include('layout/header') ?>
<?php $produk = $produk ?? []; ?>
<div class="row">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <?php if (!empty($produk['gambar'])): ?>
            <img src="<?= base_url('assets/img/' . $produk['gambar']) ?>" class="detail-image rounded-4" alt="<?= esc($produk['nama_produk'] ?? '') ?>">
        <?php else: ?>
            <div class="detail-image rounded-4 d-flex align-items-center justify-content-center"><i class="bi bi-image fs-1 text-secondary"></i></div>
        <?php endif; ?>
    </div>
    <div class="col-lg-6">
        <div class="detail-panel card h-100">
        <div class="eyebrow mb-3">Detail produk</div>
        <h1><?= esc($produk['nama_produk'] ?? '') ?></h1>
        <?php if (!empty($produk['id_penjual'])): ?><a class="seller-link" href="<?= base_url('profil/' . $produk['id_penjual']) ?>"><i class="bi bi-person-circle me-1"></i><?= esc($produk['nama_penjual'] ?? 'Lihat penjual') ?></a><?php endif; ?>
        <h3 class="price my-4">
            Rp <?= number_format((float)($produk['harga'] ?? 0), 0, ',', '.') ?>
        </h3>
        <p class="text-secondary"><?= esc($produk['deskripsi'] ?? '') ?></p>
        <div class="border-top pt-3 mt-4 mb-4"><span class="stock"><i class="bi bi-box-seam me-2"></i>Stok tersedia: <strong><?= $produk['stok'] ?? 0 ?></strong></span></div>
        <?php if (session()->get('logged_in') && ($produk['stok'] ?? 0) > 0): ?>
            <form action="<?= base_url('produk/beli/' . $produk['id_produk']) ?>" method="post" class="d-flex flex-wrap gap-2 align-items-end">
                <div>
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input id="jumlah" name="jumlah" type="number" min="1" max="<?= (int) $produk['stok'] ?>" value="1" class="form-control" style="max-width: 110px;">
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-bag-plus me-2"></i>Beli sekarang</button>
            </form>
        <?php elseif (!session()->get('logged_in')): ?>
            <a href="<?= base_url('login') ?>" class="btn btn-primary"><i class="bi bi-box-arrow-in-right me-2"></i>Login untuk membeli</a>
        <?php else: ?>
            <span class="badge text-bg-warning">Stok habis</span>
        <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->include('layout/footer') ?>
