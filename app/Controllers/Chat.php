<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;
use App\Models\UserModel;

class Chat extends BaseController
{
    private UserModel $userModel;
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->userModel = new UserModel();
    }

    public function index(?int $userId = null)
    {
        $viewerId = (int) session()->get('user_id');
        $contact = $userId ? $this->userModel->find($userId) : null;
        $contacts = $this->contacts($viewerId);
        $messages = [];

        if ($contact !== null && (int) $contact['id'] !== $viewerId) {
            $messages = $this->db->query(
                'SELECT pesan.*, pengirim.nama AS nama_pengirim FROM pesan
                 JOIN users pengirim ON pengirim.id = pesan.id_pengirim
                 WHERE (id_pengirim = ? AND id_penerima = ?)
                    OR (id_pengirim = ? AND id_penerima = ?)
                 ORDER BY pesan.created_at ASC',
                [$viewerId, $userId, $userId, $viewerId]
            )->getResultArray();

            $this->db->table('pesan')
                ->where('id_pengirim', $userId)
                ->where('id_penerima', $viewerId)
                ->where('dibaca_pada IS NULL', null, false)
                ->update(['dibaca_pada' => date('Y-m-d H:i:s')]);
        }

        return view('chat/index', [
            'title' => $contact ? 'Chat dengan ' . $contact['nama'] : 'Pesan',
            'contacts' => $contacts,
            'contact' => $contact,
            'messages' => $messages,
        ]);
    }

    public function send($userId)
    {
        $senderId = (int) session()->get('user_id');
        $recipientId = (int) $userId;
        $message = trim((string) $this->request->getPost('pesan'));

        if ($senderId === $recipientId || $this->userModel->find($recipientId) === null || $message === '') {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(422)->setJSON(['error' => 'Pesan tidak dapat dikirim.']);
            }

            return redirect()->back()->with('error', 'Pesan tidak dapat dikirim.');
        }

        $inserted = $this->db->table('pesan')->insert([
            'id_pengirim' => $senderId,
            'id_penerima' => $recipientId,
            'pesan' => $message,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($this->request->isAJAX()) {
            if (!$inserted) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Pesan gagal dikirim.']);
            }

            return $this->response->setJSON([
                'message' => [
                    'id_pesan' => $this->db->insertID(),
                    'id_pengirim' => $senderId,
                    'pesan' => $message,
                    'created_at' => date('Y-m-d H:i:s'),
                ],
            ]);
        }

        return redirect()->to(base_url('chat/' . $recipientId));
    }

    public function messages($userId)
    {
        $viewerId = (int) session()->get('user_id');
        $recipientId = (int) $userId;
        $after = max(0, (int) $this->request->getGet('after'));

        if ($viewerId === $recipientId || $this->userModel->find($recipientId) === null) {
            return $this->response->setStatusCode(404)->setJSON(['messages' => []]);
        }

        $messages = $this->db->query(
            'SELECT id_pesan, id_pengirim, pesan, created_at FROM pesan
             WHERE id_pesan > ?
               AND ((id_pengirim = ? AND id_penerima = ?)
                 OR (id_pengirim = ? AND id_penerima = ?))
             ORDER BY id_pesan ASC',
            [$after, $viewerId, $recipientId, $recipientId, $viewerId]
        )->getResultArray();

        $this->db->table('pesan')
            ->where('id_pengirim', $recipientId)
            ->where('id_penerima', $viewerId)
            ->where('dibaca_pada IS NULL', null, false)
            ->update(['dibaca_pada' => date('Y-m-d H:i:s')]);

        return $this->response->setJSON(['messages' => $messages]);
    }

    private function contacts(int $viewerId): array
    {
        return $this->db->query(
            'SELECT users.id, users.nama, users.last_seen_at,
                    (SELECT pesan FROM pesan p WHERE
                        (p.id_pengirim = users.id AND p.id_penerima = ?)
                        OR (p.id_pengirim = ? AND p.id_penerima = users.id)
                     ORDER BY p.created_at DESC LIMIT 1) AS pesan_terakhir
             FROM users
             WHERE users.id != ?
             ORDER BY users.nama ASC',
            [$viewerId, $viewerId, $viewerId]
        )->getResultArray();
    }
}
