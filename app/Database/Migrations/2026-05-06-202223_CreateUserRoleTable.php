<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRoleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'role' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'utilisateur'],
                'default' => 'utilisateur',
                'null' => false,
            ],
        ]);

        // Clé primaire
        $this->forge->addKey('id', true);

        $this->forge->createTable('user_role');
    }

    public function down()
    {
        $this->forge->dropTable('user_role');
    }
}
