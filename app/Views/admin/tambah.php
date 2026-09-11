<?= $this->include('layout/header') ?>
<div class="page-heading">
    <div class="eyebrow">Katalog</div>
    <h1 class="section-title mb-1">Tambah produk</h1>
    <p class="text-secondary mb-0">Lengkapi informasi produk baru untuk ditampilkan di etalase.</p>
</div>
<?= $this->include('partials/flash_messages') ?>
<?php
$product = [];
$categories = $kategori ?? [];
$action = base_url('admin/simpan');
$submitLabel = 'Simpan produk';
include APPPATH . 'Views/partials/product_fields.php';
?>
<?= $this->include('layout/footer') ?>
