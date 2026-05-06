<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegimeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
            ],
            'pct_viande' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'pct_poisson' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'pct_volaille' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('regime');
    }

    public function down()
    {
        $this->forge->dropTable('regime');
    }
}
