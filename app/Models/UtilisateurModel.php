<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table            = 'utilisateur';

    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;

    protected $returnType       = 'array';

    protected $protectFields    = true;

    protected $allowedFields = [
        'id_role',
        'nom',
        'email',
        'mot_de_passe',
        'date_naissance',
        'genre',
        'poids_actuel',
        'taille',
        'est_gold',
        'solde_monnaie'
    ];

    // Pas de created_at / updated_at dans ta table
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [

        'id_role' => 'required|integer',

        'nom' => 'required|min_length[3]|max_length[100]',

        'email' => 'required|valid_email|is_unique[utilisateur.email,id,{id}]',

        'mot_de_passe' => 'required|min_length[6]',

        'date_naissance' => 'required|valid_date',

        'genre' => 'required|in_list[homme,femme]',

        'poids_actuel' => 'required|decimal',

        'taille' => 'required|decimal',

        'est_gold' => 'permit_empty|in_list[0,1]',

        'solde_monnaie' => 'permit_empty|decimal'
    ];

    protected $validationMessages = [

        'email' => [
            'valid_email' => 'Adresse email invalide.',
            'is_unique'   => 'Cet email existe déjà.'
        ],

        'genre' => [
            'in_list' => 'Le genre doit être homme ou femme.'
        ]
    ];
}