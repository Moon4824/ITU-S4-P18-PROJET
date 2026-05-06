<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegimeDetailTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'id_regime' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'duree' => [
                'type' => 'INT',
            ],
            'prix' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'variation_poids' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_regime', 'regime', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('regime_detail');
    }

    public function down()
    {
        $this->forge->dropTable('regime_detail');
    }
}
