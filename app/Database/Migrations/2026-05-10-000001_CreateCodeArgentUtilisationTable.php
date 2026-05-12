<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCodeArgentUtilisationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'          => 'INT',
                'auto_increment' => true,
            ],
            'id_code_argent' => [
                'type' => 'INT',
            ],
            'id_utilisateur' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'montant_credit' => [
                'type'       => 'DECIMAL',
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
        $this->forge->addForeignKey('id_code_argent', 'code_argent', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_utilisateur', 'utilisateur', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('code_argent_utilisation');
    }

    public function down()
    {
        $this->forge->dropTable('code_argent_utilisation');
    }
}