<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCodeArgentTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'valeur' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'est_valide' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'id_utilisateur' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');

        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('code_argent');
    }

    public function down()
    {
        $this->forge->dropTable('code_argent');
    }
}
