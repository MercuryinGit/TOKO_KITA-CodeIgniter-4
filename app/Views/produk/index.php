<?= $this->include('layout/header') ?>
<div class="page-heading d-flex flex-column flex-md-row justify-content-between gap-2">
    <div><div class="eyebrow">Etalase toko</div><h1 class="section-title mb-1">Semua produk</h1><p class="text-secondary mb-0">Pilih yang paling cocok untuk kebutuhanmu.</p></div>
    <span class="badge text-bg-light align-self-md-center px-3 py-2"><?= count($produk ?? []) ?> produk</span>
</div>
<form method="get" action="<?= base_url('produk') ?>" class="card p-3 p-md-4 mb-5">
    <div class="row align-items-end g-3">
        <div class="col-12 col-md-8 col-lg-6">
            <label for="kategori" class="form-label fw-bold">Pilih kategori</label>
            <select id="kategori" name="kategori" class="form-select" onchange="this.form.submit()">
                <option value="">Semua kategori</option>
                <?php foreach (($kategori ?? []) as $item): ?>
                    <option value="<?= $item['id_kategori'] ?>" <?= (int) ($kategoriAktif ?? 0) === (int) $item['id_kategori'] ? 'selected' : '' ?>>
                        <?= esc($item['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-auto">
            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel me-2"></i>Terapkan filter</button>
        </div>
        <?php if (!empty($kategoriAktif)): ?>
            <div class="col-12 col-md-auto">
                <a href="<?= base_url('produk') ?>" class="btn btn-light">Reset</a>
            </div>
        <?php endif; ?>
    </div>
</form>
<div class="row g-4">
<?php if (!empty($produk) && is_array($produk)): ?>
    <?php foreach ($produk as $p): ?>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card product-card">
                <?php if (!empty($p['gambar'])): ?>
                    <img src="<?= base_url('assets/img/' . $p['gambar']) ?>" class="card-img-top" alt="<?= esc($p['nama_produk']) ?>">
                <?php else: ?>
                    <div class="card-img-top d-flex align-items-center justify-content-center"><i class="bi bi-image fs-1 text-secondary"></i></div>
                <?php endif; ?>
                <div class="card-body p-4">
                    <?php if (!empty($p['nama_kategori'])): ?><div class="small text-uppercase text-secondary mb-2"><?= esc($p['nama_kategori']) ?></div><?php endif; ?>
                    <h5 class="product-name mb-1"><?= esc($p['nama_produk']) ?></h5>
                    <?php if (!empty($p['id_penjual'])): ?><a class="small text-secondary mb-2" href="<?= base_url('profil/' . $p['id_penjual']) ?>">Dijual oleh <?= esc($p['nama_penjual'] ?? 'User') ?></a><?php endif; ?>
                    <p class="text-secondary small"><?= esc(mb_strimwidth($p['deskripsi'] ?? '', 0, 110, '...')) ?></p>
                    <div class="d-flex justify-content-between align-items-center mb-3"><span class="price">Rp <?= number_format($p['harga'], 0, ',', '.') ?></span><span class="stock"><i class="bi bi-box-seam me-1"></i><?= $p['stok'] ?> stok</span></div>
                    <a href="<?= base_url('produk/detail/' . $p['id_produk']) ?>" class="btn btn-primary w-100">Lihat detail</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="col-12">
        <div class="alert alert-info">
            <?php if (!empty($kategoriAktif)): ?>
                Belum ada produk pada kategori yang dipilih.
            <?php else: ?>
                Belum ada produk yang tersedia.
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
</div>
<?= $this->include('layout/footer') ?>
