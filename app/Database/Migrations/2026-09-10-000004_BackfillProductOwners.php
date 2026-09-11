<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BackfillProductOwners extends Migration
{
    public function up()
    {
        $owner = $this->db->query(
            "SELECT id FROM users WHERE role = 'admin' ORDER BY id ASC LIMIT 1"
        )->getRowArray();

        if ($owner === null) {
            $owner = $this->db->query(
                'SELECT id FROM users ORDER BY id ASC LIMIT 1'
            )->getRowArray();
        }

        if ($owner !== null) {
            $this->db->query(
                'UPDATE produk SET id_penjual = ? WHERE id_penjual IS NULL',
                [(int) $owner['id']]
            );
        }
    }

    public function down()
    {
        $this->db->query('UPDATE produk SET id_penjual = NULL');
    }
}
