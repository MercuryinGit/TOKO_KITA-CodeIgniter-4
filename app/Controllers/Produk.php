<?php

namespace App\Controllers;

use App\Models\ProdukModel;
use App\Models\KategoriModel;

class Produk extends BaseController
{
    private ProdukModel $produkModel;
    private KategoriModel $kategoriModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $kategoriId = filter_var(
            $this->request->getGet('kategori'),
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        ) ?: 0;

        $data = [
            'title' => 'Daftar Produk',
            'produk' => $this->produkModel->findAllWithCategory($kategoriId ?: null),
            'kategori' => $this->kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'kategoriAktif' => $kategoriId,
        ];

        return view('produk/index', $data);
    }

    public function detail($id)
    {
        $produk = $this->produkModel->findWithSeller((int) $id);
        if ($produk === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('produk/detail', [
            'title' => $produk['nama_produk'],
            'produk' => $produk,
        ]);
    }
}
