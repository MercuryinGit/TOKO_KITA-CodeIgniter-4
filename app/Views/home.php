<?= $this->include('layout/header') ?>
<section class="hero mb-5">
    <div class="p-4 p-lg-5">
        <div class="hero-copy">
            <div class="eyebrow mb-3">Koleksi pilihan minggu ini</div>
            <h1>Barang baik, dipilih dengan tenang.</h1>
        <?php if (session()->get('logged_in')): ?>
            <p class="lead mb-2">Senang melihatmu lagi, <strong><?= esc(session()->get('nama')) ?></strong>.</p>
        <?php endif; ?>
            <p>Temukan produk berkualitas untuk kebutuhan sehari-hari, dikurasi dengan harga yang tetap masuk akal.</p>
            <div class="hero-actions d-flex flex-wrap gap-2 mt-4">
                <a href="<?= base_url('produk') ?>" class="btn btn-primary btn-lg"><i class="bi bi-arrow-right me-2"></i>Jelajahi Produk</a>
                <?php if (!session()->get('logged_in')): ?>
                    <a href="<?= base_url('register') ?>" class="btn btn-outline-dark btn-lg">Buat Akun</a>
                <?php endif; ?>
            </div>
            <div class="hero-meta row g-3">
                <div class="col-6 col-md-4"><strong>100%</strong><span>Produk terkurasi</span></div>
                <div class="col-6 col-md-4"><strong>24/7</strong><span>Etalase terbuka</span></div>
            </div>
        </div>
    </div>
</section>

<div class="page-heading d-flex flex-column flex-md-row justify-content-between gap-2">
    <div><div class="eyebrow">Pilihan untukmu</div><h2 class="section-title mb-0">Produk terbaru</h2></div>
    <a href="<?= base_url('produk') ?>" class="align-self-md-end">Lihat semua <i class="bi bi-arrow-up-right"></i></a>
</div>
<div class="row g-4">
<?php foreach (($produk ?? []) as $p) { ?>
    <div class="col-12 col-sm-6 col-lg-3">
        <article class="card product-card">
            <?php if (!empty($p['gambar'])): ?>
                <img src="<?= base_url('assets/img/' . $p['gambar']) ?>" class="card-img-top" alt="<?= esc($p['nama_produk']) ?>">
            <?php else: ?>
                <div class="card-img-top d-flex align-items-center justify-content-center"><i class="bi bi-image fs-1 text-secondary"></i></div>
            <?php endif; ?>
            <div class="card-body p-4">
                <div class="small text-uppercase text-secondary mb-2">Produk pilihan</div>
                <h5 class="product-name card-title"><?= esc($p['nama_produk']) ?></h5>
                <div class="price mb-3">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                <a href="<?= base_url('produk/detail/' . $p['id_produk']) ?>" class="btn btn-dark w-100">Lihat detail <i class="bi bi-arrow-up-right ms-1"></i></a>
            </div>
        </article>
    </div>
<?php } ?>
</div>
<?= $this->include('layout/footer') ?>
