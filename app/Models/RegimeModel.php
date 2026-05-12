<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table      = 'regime';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'pct_viande', 'pct_poisson', 'pct_volaille'];

    /**
     * Récupère un régime avec ses détails de prix/variation
     */
    public function getRegimeWithDetails($id)
    {
        return $this->select('regime.*, regime_detail.duree, regime_detail.prix, regime_detail.variation_poids')
                    ->join('regime_detail', 'regime_detail.id_regime = regime.id')
                    ->where('regime.id', $id)
                    ->findAll();
    }

    public function getAllDetails()
    {
        return $this->select('regime.*, regime_detail.duree, regime_detail.prix, regime_detail.variation_poids')
                    ->join('regime_detail', 'regime_detail.id_regime = regime.id')
                    ->findAll();
    }

}