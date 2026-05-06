<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSportTable extends Migration
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('sport');
    }

    public function down()
    {
        $this->forge->dropTable('sport');
    }
}
