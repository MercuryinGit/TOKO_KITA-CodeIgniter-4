<?= $this->include('layout/header') ?>
<?php $title = $title ?? 'Jual Produk'; ?>
<div class="page-heading">
    <div class="eyebrow">Lapak saya</div>
    <h1 class="section-title mb-1"><?= esc($title) ?></h1>
    <p class="text-secondary mb-0">Jual produkmu dan dapatkan hasilnya ke saldo akun.</p>
</div>
<?= $this->include('partials/flash_messages') ?>
<?php
$product = $product ?? [];
$categories = $categories ?? [];
include APPPATH . 'Views/partials/product_fields.php';
?>
<?= $this->include('layout/footer') ?>