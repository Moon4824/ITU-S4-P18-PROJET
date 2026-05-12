<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTypeObjectifTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
                
            ],
            'libelle' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('type_objectif');
    }

    public function down()
    {
        $this->forge->dropTable('type_objectif');
    }
}
