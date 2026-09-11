<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;
use App\Models\ProdukModel;

class Marketplace extends BaseController
{
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function saldo()
    {
        $userId = (int) session()->get('user_id');
        return view('marketplace/saldo', [
            'title' => 'Saldo Saya',
            'user' => $this->user($userId),
            'mutations' => $this->db->table('saldo_mutasi')
                ->where('id_user', $userId)
                ->orderBy('created_at', 'DESC')
                ->get(20)
                ->getResultArray(),
        ]);
    }

    public function topup()
    {
        $amount = (int) $this->request->getPost('jumlah');
        if ($amount < 10000) {
            return redirect()->back()->with('error', 'Minimal top up adalah Rp 10.000.');
        }

        $userId = (int) session()->get('user_id');
        $this->db->transBegin();
        $this->db->query('UPDATE users SET saldo = saldo + ? WHERE id = ?', [$amount, $userId]);
        $this->db->table('saldo_mutasi')->insert([
            'id_user' => $userId,
            'tipe' => 'topup',
            'jumlah' => $amount,
            'keterangan' => 'Top up saldo',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            return redirect()->back()->with('error', 'Top up gagal diproses.');
        }

        return redirect()->to(base_url('saldo'))->with('success', 'Saldo berhasil ditambahkan.');
    }

    public function beli($id)
    {
        $quantity = max(1, (int) $this->request->getPost('jumlah'));
        $buyerId = (int) session()->get('user_id');
        $now = date('Y-m-d H:i:s');

        $this->db->transStart();
        $product = $this->db->query(
            'SELECT * FROM produk WHERE id_produk = ? FOR UPDATE',
            [$id]
        )->getRowArray();

        if ($product === null || (int) $product['stok'] < $quantity) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Produk tidak tersedia atau stok tidak mencukupi.');
        }

        if (!empty($product['id_penjual']) && (int) $product['id_penjual'] === $buyerId) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Kamu tidak dapat membeli produk sendiri.');
        }

        $total = (int) $product['harga'] * $quantity;
        $buyer = $this->db->query('SELECT saldo FROM users WHERE id = ? FOR UPDATE', [$buyerId])->getRowArray();
        if ($buyer === null || (int) $buyer['saldo'] < $total) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Saldo tidak mencukupi. Silakan top up terlebih dahulu.');
        }

        $this->db->query('UPDATE users SET saldo = saldo - ? WHERE id = ? AND saldo >= ?', [$total, $buyerId, $total]);
        if ($this->db->affectedRows() !== 1) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Saldo berubah. Silakan coba lagi.');
        }

        $this->db->query(
            'UPDATE produk SET stok = stok - ? WHERE id_produk = ? AND stok >= ?',
            [$quantity, $id, $quantity]
        );
        if ($this->db->affectedRows() !== 1) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Stok berubah. Silakan coba lagi.');
        }

        $this->db->table('pesanan')->insert([
            'id_pembeli' => $buyerId,
            'total' => $total,
            'status' => 'paid',
            'created_at' => $now,
        ]);
        $orderId = $this->db->insertID();

        $this->db->table('pesanan_detail')->insert([
            'id_pesanan' => $orderId,
            'id_produk' => $product['id_produk'],
            'id_penjual' => $product['id_penjual'] ?: null,
            'harga_satuan' => $product['harga'],
            'jumlah' => $quantity,
            'subtotal' => $total,
        ]);
        $this->addMutation($buyerId, 'pembelian', -$total, 'Pembelian ' . $product['nama_produk'], $now);

        if (!empty($product['id_penjual']) && (int) $product['id_penjual'] !== $buyerId) {
            $sellerId = (int) $product['id_penjual'];
            $this->db->query('UPDATE users SET saldo = saldo + ? WHERE id = ?', [$total, $sellerId]);
            $this->addMutation($sellerId, 'penjualan', $total, 'Penjualan ' . $product['nama_produk'], $now);
            $this->db->table('pesan')->insert([
                'id_pengirim' => $buyerId,
                'id_penerima' => $sellerId,
                'pesan' => sprintf(
                    'Pesanan baru: %s | Kuantitas: %d | Total: Rp %s',
                    $product['nama_produk'],
                    $quantity,
                    number_format($total, 0, ',', '.')
                ),
                'created_at' => $now,
            ]);
        }

        if (!$this->db->transStatus()) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Pembelian gagal diproses.');
        }

        $this->db->transCommit();

        return redirect()->to(base_url('saldo'))->with('success', 'Pembelian berhasil.');
    }

    public function detail($id)
    {
        $product = (new ProdukModel())->find($id);
        if ($product === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('produk/detail', [
            'title' => $product['nama_produk'],
            'produk' => $product,
        ]);
    }

    private function addMutation(int $userId, string $type, int $amount, string $description, string $date): void
    {
        $this->db->table('saldo_mutasi')->insert([
            'id_user' => $userId,
            'tipe' => $type,
            'jumlah' => $amount,
            'keterangan' => $description,
            'created_at' => $date,
        ]);
    }

    private function user(int $id): array
    {
        return $this->db->table('users')->where('id', $id)->get()->getRowArray() ?? [];
    }
}
