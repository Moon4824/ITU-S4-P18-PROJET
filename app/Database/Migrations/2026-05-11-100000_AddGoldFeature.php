<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoldFeature extends Migration
{
    public function up()
    {
        // Créer table gold_config
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `gold_config` (
                `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `prix` DECIMAL(10,2) NOT NULL DEFAULT 29.99,
                `remise_pct` INT NOT NULL DEFAULT 15,
                `actif` TINYINT NOT NULL DEFAULT 1,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // Insérer valeur par défaut si table est vide
        $count = $this->db->table('gold_config')->countAll();
        if ($count === 0) {
            $data = [
                'prix'       => 29.99,
                'remise_pct' => 15,
                'actif'      => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->db->table('gold_config')->insert($data);
        }

        // Créer table payments
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `payments` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT UNSIGNED NOT NULL,
                `product` VARCHAR(50) NOT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                KEY `idx_user_id` (`user_id`),
                CONSTRAINT `fk_payments_user` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function down()
    {
        // Supprimer table payments
        $this->forge->dropTable('payments');

        // Supprimer table gold_config
        $this->forge->dropTable('gold_config');
    }
}
