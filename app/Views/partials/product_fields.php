<?php
$product = $product ?? [];
$categories = $categories ?? [];
$action = $action ?? '';
$submitLabel = $submitLabel ?? 'Simpan';
$isEdit = !empty($product['id_produk']);
?>
<form class="card p-4 p-lg-5" action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="id_kategori" class="form-label">Kategori</label>
        <select id="id_kategori" name="id_kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= esc($category['id_kategori']) ?>" <?= (string) ($category['id_kategori'] ?? '') === (string) ($product['id_kategori'] ?? '') ? 'selected' : '' ?>>
                    <?= esc($category['nama_kategori']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="nama_produk" class="form-label">Nama Produk</label>
        <input type="text" id="nama_produk" name="nama_produk" class="form-control" value="<?= esc($product['nama_produk'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" id="harga" name="harga" class="form-control" value="<?= esc($product['harga'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" id="stok" name="stok" class="form-control" value="<?= esc($product['stok'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label for="gambar" class="form-label">Gambar</label>
        <input type="file" id="gambar" name="gambar" class="form-control" accept="image/jpeg,image/png,image/webp">
        <?php if ($isEdit && !empty($product['gambar'])): ?>
            <small class="text-muted">Gambar saat ini: <?= esc($product['gambar']) ?></small>
        <?php else: ?>
            <small class="text-muted">Format JPG, PNG, atau WebP. Maksimal 2 MB.</small>
        <?php endif; ?>
        <?php if ($isEdit): ?>
            <small class="d-block text-muted">Kosongkan jika tidak ingin mengganti gambar. Maksimal 2 MB.</small>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" class="form-control" rows="5"><?= esc($product['deskripsi'] ?? '') ?></textarea>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i><?= esc($submitLabel) ?></button>
        <a href="<?= base_url('admin') ?>" class="btn btn-light">Kembali</a>
    </div>
</form>
