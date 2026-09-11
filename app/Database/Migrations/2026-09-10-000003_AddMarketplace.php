<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMarketplace extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'saldo' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'default'    => 0,
                'after'      => 'is_verified',
            ],
        ]);

        $this->forge->addColumn('produk', [
            'id_penjual' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_kategori',
            ],
        ]);

        $this->forge->addField([
            'id_pesanan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pembeli' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'total' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'paid'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_pembeli');
        $this->forge->addKey('created_at');
        $this->forge->addPrimaryKey('id_pesanan');
        $this->forge->createTable('pesanan', true);

        $this->forge->addField([
            'id_detail' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pesanan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_produk' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_penjual' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'harga_satuan' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
            'jumlah' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'subtotal' => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true],
        ]);
        $this->forge->addKey('id_pesanan');
        $this->forge->addKey('id_produk');
        $this->forge->addKey('id_penjual');
        $this->forge->addPrimaryKey('id_detail');
        $this->forge->createTable('pesanan_detail', true);

        $this->forge->addField([
            'id_mutasi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tipe' => ['type' => 'VARCHAR', 'constraint' => 20],
            'jumlah' => ['type' => 'BIGINT', 'constraint' => 20],
            'keterangan' => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id_user');
        $this->forge->addKey('created_at');
        $this->forge->addPrimaryKey('id_mutasi');
        $this->forge->createTable('saldo_mutasi', true);
    }

    public function down()
    {
        $this->forge->dropTable('saldo_mutasi', true);
        $this->forge->dropTable('pesanan_detail', true);
        $this->forge->dropTable('pesanan', true);
        $this->forge->dropColumn('produk', 'id_penjual');
        $this->forge->dropColumn('users', 'saldo');
    }
}