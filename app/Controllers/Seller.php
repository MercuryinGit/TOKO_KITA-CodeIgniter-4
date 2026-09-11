<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;
use App\Models\KategoriModel;
use App\Models\ProdukModel;

class Seller extends BaseController
{
    private const IMAGE_DIRECTORY = 'assets/img';
    private const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_IMAGE_SIZE_MB = 2;

    private ProdukModel $produkModel;
    private KategoriModel $kategoriModel;
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');
        $products = $this->produkModel
            ->select('produk.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->where('id_penjual', $userId)
            ->findAll();

        $sales = $this->db->table('pesanan_detail')
            ->select('DATE(pesanan.created_at) AS tanggal, SUM(pesanan_detail.subtotal) AS pendapatan, SUM(pesanan_detail.jumlah) AS unit')
            ->join('pesanan', 'pesanan.id_pesanan = pesanan_detail.id_pesanan')
            ->where('pesanan_detail.id_penjual', $userId)
            ->where('pesanan.status', 'paid')
            ->groupBy('DATE(pesanan.created_at)')
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();

        return view('seller/index', [
            'title' => 'Lapak Saya',
            'products' => $products,
            'sales' => $sales,
        ]);
    }

    public function tambah()
    {
        return view('seller/form', [
            'title' => 'Jual Produk',
            'product' => [],
            'categories' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'action' => base_url('seller/simpan'),
            'submitLabel' => 'Terbitkan produk',
        ]);
    }

    public function simpan()
    {
        $upload = $this->uploadImage();
        if ($upload['error'] !== null) {
            return redirect()->back()->withInput()->with('error', $upload['error']);
        }

        $product = $this->productData($upload['name']);
        $product['id_penjual'] = (int) session()->get('user_id');

        if (!$this->produkModel->insert($product)) {
            $this->removeImage($upload['name']);
            return redirect()->back()->withInput()->with('error', 'Produk gagal disimpan.');
        }

        return redirect()->to(base_url('seller'))->with('success', 'Produk berhasil diterbitkan.');
    }

    public function edit($id)
    {
        $product = $this->ownedProduct($id);
        if ($product === null) {
            return redirect()->to(base_url('seller'))->with('error', 'Produk tidak ditemukan.');
        }

        return view('seller/form', [
            'title' => 'Edit Produk Jualan',
            'product' => $product,
            'categories' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'action' => base_url('seller/update/' . $id),
            'submitLabel' => 'Simpan perubahan',
        ]);
    }

    public function update($id)
    {
        $product = $this->ownedProduct($id);
        if ($product === null) {
            return redirect()->to(base_url('seller'))->with('error', 'Produk tidak ditemukan.');
        }

        $upload = $this->uploadImage();
        if ($upload['error'] !== null) {
            return redirect()->back()->withInput()->with('error', $upload['error']);
        }

        $imageName = $upload['name'] ?? $product['gambar'];
        if (!$this->produkModel->update($id, $this->productData($imageName))) {
            $this->removeImage($upload['name']);
            return redirect()->back()->withInput()->with('error', 'Produk gagal diperbarui.');
        }

        if ($upload['name'] !== null) {
            $this->removeImage($product['gambar'] ?? null);
        }

        return redirect()->to(base_url('seller'))->with('success', 'Produk berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $product = $this->ownedProduct($id);
        if ($product === null) {
            return redirect()->to(base_url('seller'))->with('error', 'Produk tidak ditemukan.');
        }

        if ($this->produkModel->delete($id)) {
            $this->removeImage($product['gambar'] ?? null);
        }

        return redirect()->to(base_url('seller'))->with('success', 'Produk berhasil dihapus.');
    }

    private function ownedProduct($id): ?array
    {
        return $this->produkModel
            ->where('id_penjual', (int) session()->get('user_id'))
            ->find($id);
    }

    private function productData(?string $imageName): array
    {
        return [
            'id_kategori' => $this->request->getPost('id_kategori'),
            'nama_produk' => trim((string) $this->request->getPost('nama_produk')),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'gambar' => $imageName,
            'deskripsi' => trim((string) $this->request->getPost('deskripsi')),
        ];
    }

    private function uploadImage(): array
    {
        $image = $this->request->getFile('gambar');
        if ($image === null || $image->getError() === UPLOAD_ERR_NO_FILE) {
            return ['name' => null, 'error' => null];
        }

        if (!$image->isValid()) {
            return ['name' => null, 'error' => 'Gambar gagal diupload.'];
        }

        if (!in_array($image->getMimeType(), self::ALLOWED_IMAGE_TYPES, true)
            || $image->getSizeByUnit('mb') > self::MAX_IMAGE_SIZE_MB) {
            return ['name' => null, 'error' => 'Gambar harus JPG, PNG, atau WebP dan maksimal 2 MB.'];
        }

        $name = $image->getRandomName();
        $image->move(FCPATH . self::IMAGE_DIRECTORY, $name);
        return ['name' => $name, 'error' => null];
    }

    private function removeImage(?string $imageName): void
    {
        if (empty($imageName)) {
            return;
        }

        $path = FCPATH . self::IMAGE_DIRECTORY . '/' . basename($imageName);
        if (is_file($path)) {
            unlink($path);
        }
    }
}
