<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuggestionProgrammeDetailTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'id_suggestion' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_regime' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'id_sport' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('id_suggestion', 'suggestion_programme', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_regime', 'regime', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_sport', 'sport', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('suggestion_programme_detail');
    }

    public function down()
    {
        $this->forge->dropTable('suggestion_programme_detail');
    }
}
