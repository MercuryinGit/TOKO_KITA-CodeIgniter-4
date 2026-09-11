<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVerificationToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'is_verified' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'after'      => 'role',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_verified');
    }
}