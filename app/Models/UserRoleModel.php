<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model pour la table `user_role`
 */
class UserRoleModel extends Model
{
    protected $table      = 'user_role';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $allowedFields  = [
        'role',
    ];

    protected $useTimestamps = false;

    public function findByRole(string $role): ?array
    {
        return $this->where('role', $role)->first();
    }

    public function getRoleLabelById(int $id): ?string
    {
        $role = $this->find($id);

        return $role['role'] ?? null;
    }
}
