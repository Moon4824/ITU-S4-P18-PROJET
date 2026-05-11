<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixCodeArgentUtilisationConstraint extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        
        // Retirer la contrainte UNIQUE uniquement sur id_code_argent
        $this->db->query('ALTER TABLE code_argent_utilisation DROP INDEX id_code_argent');
        
        // Ajouter une contrainte UNIQUE composée sur (id_code_argent, id_utilisateur)
        $this->db->query('ALTER TABLE code_argent_utilisation ADD UNIQUE KEY code_utilisateur (id_code_argent, id_utilisateur)');
        
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        
        // Retirer la contrainte composée
        $this->db->query('ALTER TABLE code_argent_utilisation DROP INDEX code_utilisateur');
        
        // Restaurer la contrainte originale
        $this->db->query('ALTER TABLE code_argent_utilisation ADD UNIQUE KEY id_code_argent (id_code_argent)');
        
        $this->db->enableForeignKeyChecks();
    }
}
