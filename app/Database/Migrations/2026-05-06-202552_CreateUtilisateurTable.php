<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUtilisateurTable extends Migration
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
            'id_role' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
                'unique' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
                'unique' => true,
            ],
            'mot_de_passe' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'date_naissance' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'genre' => [
                'type' => 'ENUM',
                'constraint' => ['homme', 'femme'],
                'null' => false,
            ],
            'poids_actuel' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => false,
            ],
            'taille' => [
                'type' => 'DECIMAL',
                'constraint' => '4,2',
                'null' => false,
            ],
            'est_gold' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => false,
            ],
            'solde_monnaie' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
                'null' => false,
            ],
        ]);

        // Clé primaire
        $this->forge->addKey('id', true);

        // Clé étrangère
        $this->forge->addForeignKey('id_role', 'user_role', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('utilisateur');

    }

    public function down()
    {
        $this->forge->dropTable('utilisateur');
    }
}
