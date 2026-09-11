<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;
use App\Models\ProdukModel;
use App\Models\UserModel;

class Profile extends BaseController
{
    private UserModel $userModel;
    private ProdukModel $produkModel;
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = new UserModel();
        $this->produkModel = new ProdukModel();
    }

    public function index(?int $id = null)
    {
        $viewerId = (int) session()->get('user_id');
        $profileId = $id ?? $viewerId;
        $user = $this->userModel->find($profileId);

        if ($user === null) {
            session()->destroy();
            return redirect()->to(base_url('login'))
                ->with('error', 'Sesi akun tidak ditemukan. Silakan login kembali.');
        }

        $isOwner = $profileId === $viewerId;
        $isFollowing = !$isOwner && $this->db->table('user_follows')
            ->where('id_follower', $viewerId)
            ->where('id_following', $profileId)
            ->countAllResults() > 0;

        $products = $this->produkModel
            ->select('produk.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->where('id_penjual', $profileId)
            ->findAll();

        $stats = [
            'followers' => $this->db->table('user_follows')->where('id_following', $profileId)->countAllResults(),
            'following' => $this->db->table('user_follows')->where('id_follower', $profileId)->countAllResults(),
            'products' => count($products),
        ];

        $salesTotal = 0;
        if ($isOwner) {
            $salesTotal = (int) ($this->db->table('pesanan_detail')
                ->selectSum('subtotal')
                ->where('id_penjual', $profileId)
                ->get()
                ->getRow('subtotal') ?? 0);
        }

        return view('profile/index', [
            'title' => $isOwner ? 'Profil Saya' : 'Profil ' . $user['nama'],
            'user' => $user,
            'products' => $products,
            'stats' => $stats,
            'isOwner' => $isOwner,
            'isFollowing' => $isFollowing,
            'salesTotal' => $salesTotal,
        ]);
    }

    public function follow($id)
    {
        $userId = (int) session()->get('user_id');
        $targetId = (int) $id;
        if ($userId === $targetId || $this->userModel->find($targetId) === null) {
            return redirect()->back()->with('error', 'User tidak dapat diikuti.');
        }

        $exists = $this->db->table('user_follows')
            ->where('id_follower', $userId)
            ->where('id_following', $targetId)
            ->countAllResults();
        if ($exists === 0) {
            $this->db->table('user_follows')->insert([
                'id_follower' => $userId,
                'id_following' => $targetId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to(base_url('profil/' . $targetId));
    }

    public function unfollow($id)
    {
        $this->db->table('user_follows')
            ->where('id_follower', (int) session()->get('user_id'))
            ->where('id_following', (int) $id)
            ->delete();

        return redirect()->to(base_url('profil/' . (int) $id));
    }

    public function updateBio()
    {
        $bio = trim((string) $this->request->getPost('bio'));
        if (mb_strlen($bio) > 500) {
            return redirect()->back()->with('error', 'Bio maksimal 500 karakter.');
        }

        $this->userModel->update((int) session()->get('user_id'), ['bio' => $bio]);
        return redirect()->to(base_url('profil'))->with('success', 'Bio berhasil diperbarui.');
    }
}
