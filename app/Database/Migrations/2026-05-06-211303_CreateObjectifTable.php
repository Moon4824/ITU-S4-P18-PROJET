<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateObjectifTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_utilisateur' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_type_objectif' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'valeur_poids' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_type_objectif', 'type_objectif', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('objectif');
    }

    public function down()
    {
        $this->forge->dropTable('objectif');
    }
}
