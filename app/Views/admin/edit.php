<?= $this->include('layout/header') ?>
<div class="page-heading">
    <div class="eyebrow">Katalog</div>
    <h1 class="section-title mb-1">Edit produk</h1>
    <p class="text-secondary mb-0">Perbarui detail produk dan simpan perubahanmu.</p>
</div>
<?= $this->include('partials/flash_messages') ?>
<?php
$product = $produk ?? [];
$categories = $kategori ?? [];
$action = base_url('admin/update/' . ($product['id_produk'] ?? ''));
$submitLabel = 'Simpan perubahan';
include APPPATH . 'Views/partials/product_fields.php';
?>
<?= $this->include('layout/footer') ?>
