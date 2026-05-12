<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeDetailModel extends Model
{
    protected $table      = 'regime_detail';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_regime', 'duree', 'prix', 'variation_poids'];

    /**
     * Suggère des régimes basés sur la différence de poids
     * @param float $diffPoids (poids_objectif - poids_actuel)
     */
    public function getSuggestions($diffPoids)
    {
        if ((float) $diffPoids === 0.0) {
            return [];
        }

        $builder = $this->db->table($this->table);
        $builder->select('regime.id AS regime_id, regime_detail.id AS detail_id, regime_detail.*, regime.nom, regime.pct_viande, regime.pct_poisson, regime.pct_volaille');
        $builder->join('regime', 'regime.id = regime_detail.id_regime');

        // Filtrage par sens de variation
        if ($diffPoids > 0) {
            $builder->where('regime_detail.variation_poids >', 0);
        } else {
            $builder->where('regime_detail.variation_poids <', 0);
        }

        $results = $builder->get()->getResultArray();

        foreach ($results as &$row) {
            // 1. Calcul du ratio pour atteindre l'objectif
            $variation = (float) $row['variation_poids'];

            if ($variation == 0.0) {
                continue;
            }

            $ratio = abs($diffPoids / $variation);
            
            // 2. Calcul de la durée totale (en jours)
            // On multiplie le ratio par la durée de référence du pack
            $row['duree_totale_calculee'] = (int) ceil($ratio * (float) $row['duree']);
            
            // 3. Calcul du prix total (CORRECTION)
            // Formule : Durée totale en jours * Prix journalier
            $row['prix_total_calcule'] = round($row['duree_totale_calculee'] * (float) $row['prix'], 2);
        }

        unset($row);

        usort($results, static function (array $left, array $right): int {
            return ($left['duree_totale_calculee'] ?? 0) <=> ($right['duree_totale_calculee'] ?? 0);
        });

        return $results;
    }
}