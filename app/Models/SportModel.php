<?php

namespace App\Models;

use CodeIgniter\Model;

class SportModel extends Model
{
    protected $table         = 'sport';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nom', 'apport_poids'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'nom'          => 'required|min_length[2]|max_length[150]',
        'apport_poids' => 'required|in_list[-1,0,1]',
    ];
}