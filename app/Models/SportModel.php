<?php

namespace App\Models;

use CodeIgniter\Model;

class SportModel extends Model
{
    protected $table      = 'sport';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'apport_poids'];

    /**
     * Récupère les sports adaptés à l'objectif
     * @param int $type (-1 pour perte, 1 pour prise, 0 pour neutre)
     */
    public function getSportsByObjectif($type)
    {
        return $this->where('apport_poids', $type)->findAll();
    }
}