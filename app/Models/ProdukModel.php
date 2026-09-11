<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    protected $allowedFields = [
        'id_kategori',
        'id_penjual',
        'nama_produk',
        'harga',
        'stok',
        'gambar',
        'deskripsi',
    ];

    public function findAllWithCategory(?int $categoryId = null): array
    {
        $query = $this->select('produk.*, kategori.nama_kategori, users.nama AS nama_penjual')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left');
        $query->join('users', 'users.id = produk.id_penjual', 'left');

        if ($categoryId !== null && $categoryId > 0) {
            $query->where('produk.id_kategori', $categoryId);
        }

        return $query->findAll();
    }

    public function findWithSeller(int $id): ?array
    {
        return $this->select('produk.*, users.nama AS nama_penjual')
            ->join('users', 'users.id = produk.id_penjual', 'left')
            ->find($id);
    }
}
