<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KategoriModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    private const IMAGE_DIRECTORY = 'assets/img';
    private const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_IMAGE_SIZE_MB = 2;

    protected $produkModel;
    protected $kategoriModel;
    protected $userModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Admin Produk',
            'produk' => $this->produkModel->findAllWithCategory()
        ];
        return view('admin/index', $data);
    }

    public function users()
    {
        if (!$this->isVerifiedAdmin()) {
            return redirect()->to(base_url('admin'))
                ->with('error', 'Hanya admin terverifikasi yang dapat mengelola pengguna.');
        }

        return view('admin/users', [
            'title' => 'Admin Pengguna',
            'users' => $this->userModel->orderBy('nama', 'ASC')->findAll(),
        ]);
    }

    public function updateUserAccess($id)
    {
        if (!$this->isVerifiedAdmin()) {
            return redirect()->to(base_url('admin'))
                ->with('error', 'Hanya admin terverifikasi yang dapat mengubah role pengguna.');
        }

        if ((int) $id === (int) session()->get('user_id')) {
            return redirect()->back()->with('error', 'Role akun sendiri tidak dapat diubah.');
        }

        $role = $this->request->getPost('role');
        $isVerified = $this->request->getPost('is_verified') === '1' ? 1 : 0;
        if (!in_array($role, ['user', 'admin'], true)) {
            return redirect()->back()->with('error', 'Role yang dipilih tidak valid.');
        }

        if ($role !== 'admin') {
            $isVerified = 0;
        }

        if (!$this->userModel->find($id)) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $this->userModel->update($id, [
            'role' => $role,
            'is_verified' => $isVerified,
        ]);

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Role pengguna berhasil diperbarui.');
    }

    public function tambah()
    {
        $data = [
            'title' => 'Tambah Produk',
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('admin/tambah', $data);
    }

    public function simpan()
    {
        $upload = $this->uploadImage();
        if ($upload['error'] !== null) {
            return redirect()->back()->withInput()->with('error', $upload['error']);
        }

        if (!$this->produkModel->insert($this->productData($upload['name']))) {
            $this->removeImage($upload['name']);
            return redirect()->back()->withInput()->with('error', 'Produk gagal disimpan.');
        }

        return redirect()->to(base_url('admin'));
    }

    public function edit($id)
    {
        $produk = $this->produkModel->find($id);
        if ($produk === null) {
            return redirect()->to(base_url('admin'))->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Produk',
            'produk' => $produk,
            'kategori' => $this->kategoriModel->findAll()
        ];
        return view('admin/edit', $data);
    }

    public function update($id)
    {
        $produk = $this->produkModel->find($id);
        if ($produk === null) {
            return redirect()->to(base_url('admin'))->with('error', 'Produk tidak ditemukan.');
        }

        $upload = $this->uploadImage();
        if ($upload['error'] !== null) {
            return redirect()->back()->withInput()->with('error', $upload['error']);
        }

        $namaGambar = $upload['name'] ?? $produk['gambar'];
        $berhasilDiubah = $this->produkModel->update($id, $this->productData($namaGambar));

        if (!$berhasilDiubah) {
            $this->removeImage($upload['name']);
            return redirect()->back()->withInput()->with('error', 'Produk gagal diperbarui.');
        }

        if ($upload['name'] !== null) {
            $this->removeImage($produk['gambar'] ?? null);
        }

        return redirect()->to(base_url('admin'));
    }

    public function hapus($id)
    {
        $produk = $this->produkModel->find($id);
        if ($produk === null) {
            return redirect()->to(base_url('admin'))->with('error', 'Produk tidak ditemukan.');
        }

        if ($this->produkModel->delete($id)) {
            $this->removeImage($produk['gambar'] ?? null);
        }

        return redirect()->to(base_url('admin'));
    }

    private function isVerifiedAdmin(): bool
    {
        return session()->get('role') === 'admin' && (bool) session()->get('is_verified');
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
        $gambar = $this->request->getFile('gambar');
        if ($gambar === null || $gambar->getError() === UPLOAD_ERR_NO_FILE) {
            return ['name' => null, 'error' => null];
        }

        if (!$gambar->isValid()) {
            return ['name' => null, 'error' => 'Gambar gagal diupload.'];
        }

        if (!in_array($gambar->getMimeType(), self::ALLOWED_IMAGE_TYPES, true)
            || $gambar->getSizeByUnit('mb') > self::MAX_IMAGE_SIZE_MB) {
            return [
                'name' => null,
                'error' => 'Gambar harus JPG, PNG, atau WebP dan maksimal 2 MB.',
            ];
        }

        $namaGambar = $gambar->getRandomName();
        $gambar->move(FCPATH . self::IMAGE_DIRECTORY, $namaGambar);

        return ['name' => $namaGambar, 'error' => null];
    }

    private function removeImage(?string $imageName): void
    {
        if (empty($imageName)) {
            return;
        }

        $imagePath = FCPATH . self::IMAGE_DIRECTORY . '/' . basename($imageName);
        if (is_file($imagePath)) {
            unlink($imagePath);
        }
    }
}
