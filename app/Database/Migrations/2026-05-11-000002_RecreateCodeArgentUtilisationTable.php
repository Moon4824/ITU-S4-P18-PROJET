<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RecreateCodeArgentUtilisationTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        
        // Supprimer la table existante
        $this->db->query('DROP TABLE IF EXISTS code_argent_utilisation');
        
        // Recréer la table avec le bon schéma
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'autoIncrement' => true,
            ],
            'id_code_argent' => [
                'type' => 'INT',
            ],
            'id_utilisateur' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'montant_credit' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'date_utilisation' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['id_code_argent', 'id_utilisateur'], 'code_utilisateur');
        $this->forge->addKey('id_utilisateur');
        $this->forge->addForeignKey('id_code_argent', 'code_argent', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id', '', 'CASCADE');

        $this->forge->createTable('code_argent_utilisation');
        
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('code_argent_utilisation');
        $this->db->enableForeignKeyChecks();
    }
}
