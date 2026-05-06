<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInterpretationIMCTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'libelle' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
            ],
            'min' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'max' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('interpretation_imc');
    }

    public function down()
    {
        $this->forge->dropTable('interpretation_imc');
    }
}
