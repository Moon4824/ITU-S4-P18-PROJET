<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table            = 'user_role';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';

    protected $protectFields    = true;

    protected $allowedFields = [
        'role'
    ];

    // Timestamps désactivés
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'role' => 'required|in_list[admin,utilisateur]'
    ];

    protected $validationMessages = [
        'role' => [
            'required' => 'Le rôle est obligatoire.',
            'in_list'  => 'Le rôle doit être admin ou utilisateur.'
        ]
    ];
}