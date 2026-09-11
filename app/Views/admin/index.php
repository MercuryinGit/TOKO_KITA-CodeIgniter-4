
<?= $this->include('layout/header') ?>
<div class="admin-toolbar">
    <div><div class="eyebrow">Ruang admin</div><h1 class="section-title mb-1">Kelola produk</h1><p class="text-secondary mb-0">Atur katalog dan stok toko dari satu tempat.</p></div>
    <div class="d-flex flex-wrap gap-2">
        <?php if (session()->get('is_verified')): ?>
            <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-primary"><i class="bi bi-people me-2"></i>Pengguna</a>
        <?php endif; ?>
        <a href="<?= base_url('admin/tambah') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Tambah produk</a>
    </div>
</div>
<div class="card table-card">
<table class="table table-hover">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php $no = 1; ?>
    <?php if (!empty($produk) && is_array($produk)): ?>
        <?php foreach ($produk as $p): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($p['nama_produk']) ?></td>
                <td><?= esc($p['nama_kategori']) ?></td>
                <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                <td><?= $p['stok'] ?></td>
                <td>
                    <a href="<?= base_url('admin/edit/' . $p['id_produk']) ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <form action="<?= base_url('admin/hapus/' . $p['id_produk']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-1"></i>Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center">Belum ada data produk.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</div>
<?= $this->include('layout/footer') ?>
