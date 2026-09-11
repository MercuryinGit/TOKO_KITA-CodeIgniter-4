<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleProductsSeeder extends Seeder
{
    private const IMAGE_FILES = [
        '1788944639_24b4e32ec3a600f40473.jpg',
        '1789003240_8cdee4f8d5214c1e1bba.jpg',
    ];

    private const CATEGORIES = [
        'Fashion',
        'Elektronik',
        'Rumah Tangga',
        'Kecantikan',
    ];

    public function run()
    {
        $categoryIds = $this->ensureCategories();
        $products = $this->products();

        foreach ($products as $index => $product) {
            $product['id_kategori'] = $categoryIds[$product['kategori']];
            $product['gambar'] = self::IMAGE_FILES[$index % count(self::IMAGE_FILES)];
            unset($product['kategori']);

            $exists = $this->db->table('produk')
                ->where('nama_produk', $product['nama_produk'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('produk')->insert($product);
            }
        }
    }

    private function ensureCategories(): array
    {
        $categoryIds = [];
        $categoryTable = $this->db->table('kategori');

        foreach (self::CATEGORIES as $categoryName) {
            $category = $categoryTable
                ->where('nama_kategori', $categoryName)
                ->get()
                ->getRowArray();

            if ($category === null) {
                $categoryTable->insert(['nama_kategori' => $categoryName]);
                $categoryIds[$categoryName] = (int) $this->db->insertID();
            } else {
                $categoryIds[$categoryName] = (int) $category['id_kategori'];
            }
        }

        return $categoryIds;
    }

    private function products(): array
    {
        return [
            ['kategori' => 'Fashion', 'nama_produk' => 'Kaos Katun Basic', 'harga' => 89000, 'stok' => 24, 'deskripsi' => 'Kaos katun nyaman untuk aktivitas harian.'],
            ['kategori' => 'Fashion', 'nama_produk' => 'Kemeja Linen Santai', 'harga' => 179000, 'stok' => 18, 'deskripsi' => 'Kemeja linen ringan dengan potongan santai.'],
            ['kategori' => 'Fashion', 'nama_produk' => 'Celana Chino Harian', 'harga' => 219000, 'stok' => 15, 'deskripsi' => 'Celana chino serbaguna untuk gaya kasual.'],
            ['kategori' => 'Fashion', 'nama_produk' => 'Tas Selempang Kanvas', 'harga' => 129000, 'stok' => 21, 'deskripsi' => 'Tas kanvas ringkas untuk membawa kebutuhan sehari-hari.'],
            ['kategori' => 'Fashion', 'nama_produk' => 'Topi Kasual Unisex', 'harga' => 69000, 'stok' => 30, 'deskripsi' => 'Topi kasual dengan bahan ringan dan nyaman.'],

            ['kategori' => 'Elektronik', 'nama_produk' => 'Lampu Meja LED', 'harga' => 149000, 'stok' => 16, 'deskripsi' => 'Lampu meja LED hemat energi dengan cahaya nyaman.'],
            ['kategori' => 'Elektronik', 'nama_produk' => 'Kabel Data Fast Charging', 'harga' => 59000, 'stok' => 40, 'deskripsi' => 'Kabel data dengan dukungan pengisian cepat.'],
            ['kategori' => 'Elektronik', 'nama_produk' => 'Headset Bluetooth Mini', 'harga' => 199000, 'stok' => 12, 'deskripsi' => 'Headset nirkabel ringkas untuk musik dan panggilan.'],
            ['kategori' => 'Elektronik', 'nama_produk' => 'Power Bank 10000mAh', 'harga' => 169000, 'stok' => 20, 'deskripsi' => 'Power bank praktis untuk perjalanan dan aktivitas harian.'],
            ['kategori' => 'Elektronik', 'nama_produk' => 'Stand Ponsel Adjustable', 'harga' => 79000, 'stok' => 28, 'deskripsi' => 'Stand ponsel dengan sudut yang dapat disesuaikan.'],

            ['kategori' => 'Rumah Tangga', 'nama_produk' => 'Kotak Penyimpanan Serbaguna', 'harga' => 99000, 'stok' => 25, 'deskripsi' => 'Kotak penyimpanan untuk menjaga rumah tetap rapi.'],
            ['kategori' => 'Rumah Tangga', 'nama_produk' => 'Botol Minum Stainless', 'harga' => 119000, 'stok' => 22, 'deskripsi' => 'Botol minum stainless yang tahan lama dan mudah dibawa.'],
            ['kategori' => 'Rumah Tangga', 'nama_produk' => 'Set Wadah Makan', 'harga' => 139000, 'stok' => 17, 'deskripsi' => 'Set wadah makan praktis untuk rumah dan kantor.'],
            ['kategori' => 'Rumah Tangga', 'nama_produk' => 'Keset Rumah Minimalis', 'harga' => 75000, 'stok' => 19, 'deskripsi' => 'Keset lembut dengan desain minimalis untuk berbagai ruangan.'],
            ['kategori' => 'Rumah Tangga', 'nama_produk' => 'Rak Dinding Compact', 'harga' => 189000, 'stok' => 10, 'deskripsi' => 'Rak dinding hemat tempat untuk menyimpan barang kecil.'],

            ['kategori' => 'Kecantikan', 'nama_produk' => 'Face Wash Gentle', 'harga' => 85000, 'stok' => 26, 'deskripsi' => 'Pembersih wajah lembut untuk pemakaian sehari-hari.'],
            ['kategori' => 'Kecantikan', 'nama_produk' => 'Body Lotion Moisture', 'harga' => 99000, 'stok' => 23, 'deskripsi' => 'Body lotion dengan tekstur ringan dan melembapkan.'],
            ['kategori' => 'Kecantikan', 'nama_produk' => 'Lip Balm Natural', 'harga' => 49000, 'stok' => 35, 'deskripsi' => 'Lip balm ringan untuk membantu menjaga kelembapan bibir.'],
            ['kategori' => 'Kecantikan', 'nama_produk' => 'Hair Serum Smooth', 'harga' => 109000, 'stok' => 14, 'deskripsi' => 'Serum rambut untuk tampilan halus dan mudah diatur.'],
            ['kategori' => 'Kecantikan', 'nama_produk' => 'Hand Cream Soft Touch', 'harga' => 65000, 'stok' => 29, 'deskripsi' => 'Krim tangan dengan tekstur ringan dan cepat meresap.'],
        ];
    }
}
