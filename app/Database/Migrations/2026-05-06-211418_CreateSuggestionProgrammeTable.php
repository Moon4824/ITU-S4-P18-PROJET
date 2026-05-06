<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuggestionProgrammeTable extends Migration
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
            'id_objectif' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'date_debut' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'duree_programme' => [
                'type' => 'INT',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);

        // Foreign keys
        $this->forge->addForeignKey(
            'id_utilisateur',
            'utilisateur',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'id_objectif',
            'objectif',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('suggestion_programme');
    }

    public function down()
    {
        $this->forge->dropTable('suggestion_programme');
    }
}
