<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectifModel extends Model
{
    protected $table      = 'objectif';
    protected $primaryKey = 'id';
    protected $returnType  = 'array';
    protected $allowedFields = [
        'id_utilisateur',
        'id_type_objectif',
        'poids_initial',
        'objectif_poids',
        'regime_id',
        'sport_id',
        'IMC_initial',
        'duree_objectif',
        'prix_total',
        'date_debut',
    ];

    public function findLatestByUser(int $userId): ?array
    {
        return $this->select('objectif.*, type_objectif.libelle AS type_objectif_label, regime.nom AS regime_label')
            ->join('type_objectif', 'type_objectif.id = objectif.id_type_objectif', 'left')
            ->join('regime', 'regime.id = objectif.regime_id', 'left')
            ->where('objectif.id_utilisateur', $userId)
            ->orderBy('objectif.id', 'DESC')
            ->first();
    }
}