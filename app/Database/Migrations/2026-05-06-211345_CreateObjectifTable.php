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
            'poids_initial' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'objectif_poids' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'regime_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'sport_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'IMC_initial' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'duree_objectif' => [
                'type' => 'INT',
                'null' => false,
            ],
            'prix_total' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
            ],
            'date_debut' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_type_objectif', 'type_objectif', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('regime_id', 'regime', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sport_id', 'sport', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('objectif');
    }

    public function down()
    {
        $this->forge->dropTable('objectif');
    }
}
