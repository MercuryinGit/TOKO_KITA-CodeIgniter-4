<?= $this->include('layout/header') ?>
<?php $products = $products ?? []; $sales = $sales ?? []; ?>
<div class="admin-toolbar">
    <div><div class="eyebrow">Lapak saya</div><h1 class="section-title mb-1">Jual Produk</h1><p class="text-secondary mb-0">Kelola produk dan pantau pendapatan penjualanmu.</p></div>
    <a href="<?= base_url('seller/tambah') ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Jual produk baru</a>
</div>
<?= $this->include('partials/flash_messages') ?>
<div class="card p-4 mb-4">
    <h2 class="h5">Chart Penjualan</h2>
    <div style="height: 280px"><canvas id="salesChart"></canvas></div>
</div>
<div class="card table-card">
    <table class="table table-hover mb-0">
        <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= esc($product['nama_produk']) ?></td>
                <td><?= esc($product['nama_kategori'] ?? '-') ?></td>
                <td>Rp <?= number_format($product['harga'], 0, ',', '.') ?></td>
                <td><?= (int) $product['stok'] ?></td>
                <td>
                    <a href="<?= base_url('seller/edit/' . $product['id_produk']) ?>" class="btn btn-outline-primary btn-sm">Edit</a>
                    <form action="<?= base_url('seller/hapus/' . $product['id_produk']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus produk ini?')"><button class="btn btn-outline-danger btn-sm">Hapus</button></form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($products)): ?><tr><td colspan="5" class="text-center">Belum ada produk jualan.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($sales, 'tanggal')) ?>,
        datasets: [{
            label: 'Pendapatan',
            data: <?= json_encode(array_map('intval', array_column($sales, 'pendapatan'))) ?>,
            borderColor: '#953038',
            backgroundColor: 'rgba(195, 83, 87, 0.18)',
            fill: true,
            tension: 0.3
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
<?= $this->include('layout/footer') ?>