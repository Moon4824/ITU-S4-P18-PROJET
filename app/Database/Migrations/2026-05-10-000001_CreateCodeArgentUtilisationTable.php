<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCodeArgentUtilisationTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        
        $this->db->query('CREATE TABLE IF NOT EXISTS code_argent_utilisation (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_code_argent INT NOT NULL,
            id_utilisateur INT UNSIGNED NOT NULL,
            montant_credit DECIMAL(10,2) NOT NULL,
            date_utilisation DATETIME NULL,
            UNIQUE KEY code_utilisateur (id_code_argent, id_utilisateur),
            KEY id_utilisateur (id_utilisateur),
            CONSTRAINT code_argent_utilisation_id_code_argent_foreign FOREIGN KEY (id_code_argent) 
                REFERENCES code_argent(id) ON DELETE CASCADE,
            CONSTRAINT code_argent_utilisation_id_utilisateur_foreign FOREIGN KEY (id_utilisateur) 
                REFERENCES utilisateur(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
        
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->db->query('DROP TABLE IF EXISTS code_argent_utilisation');
        $this->db->enableForeignKeyChecks();
    }
}