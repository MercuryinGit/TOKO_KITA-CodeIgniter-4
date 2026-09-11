<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSocialProfiles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'bio' => ['type' => 'TEXT', 'null' => true, 'after' => 'saldo'],
            'last_seen_at' => ['type' => 'DATETIME', 'null' => true, 'after' => 'bio'],
        ]);

        $this->forge->addField([
            'id_follow' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_follower' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_following' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_follow');
        $this->forge->addKey(['id_follower', 'id_following'], false, true);
        $this->forge->addKey('id_following');
        $this->forge->createTable('user_follows', true);

        $this->forge->addField([
            'id_pesan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_pengirim' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_penerima' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pesan' => ['type' => 'TEXT'],
            'dibaca_pada' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_pesan');
        $this->forge->addKey(['id_pengirim', 'id_penerima']);
        $this->forge->addKey('created_at');
        $this->forge->createTable('pesan', true);
    }

    public function down()
    {
        $this->forge->dropTable('pesan', true);
        $this->forge->dropTable('user_follows', true);
        $this->forge->dropColumn('users', ['bio', 'last_seen_at']);
    }
}
