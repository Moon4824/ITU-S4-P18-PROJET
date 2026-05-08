<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
         // ROLES
        $this->db->table('user_role')->insertBatch([
            ['role' => 'admin'],
            ['role' => 'utilisateur'],
        ]);

    }
}
