<?php

namespace App\Controllers;

use App\Models\ProdukModel;

class Home extends BaseController
{
    private ProdukModel $produkModel;

    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Toko Sederhana',
            'produk' => $this->produkModel->findAll(),
        ];

        return view('home', $data);
    }
}
