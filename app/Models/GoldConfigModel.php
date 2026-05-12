<?php

namespace App\Models;

use CodeIgniter\Model;

class GoldConfigModel extends Model
{
    protected $table            = 'gold_config';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['prix', 'remise_pct', 'actif'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'prix'        => 'required|decimal|greater_than[0]',
        'remise_pct'  => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'actif'       => 'required|in_list[0,1]',
    ];
    protected $validationMessages   = [
        'prix' => [
            'required' => 'Le prix est obligatoire',
            'decimal'  => 'Le prix doit être un nombre décimal',
            'greater_than' => 'Le prix doit être supérieur à 0',
        ],
        'remise_pct' => [
            'required' => 'Le pourcentage de remise est obligatoire',
            'integer'  => 'Le pourcentage doit être un entier',
            'less_than_equal_to' => 'Le pourcentage ne peut pas dépasser 100%',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Obtenir la configuration Gold active
     * @return array<string, mixed>|null
     */
    public function getActiveConfig(): array|null
    {
        return $this->where('actif', 1)->first();
    }

    /**
     * Mettre à jour la configuration Gold
     * @param float $prix
     * @param int $remise_pct
     * @return bool|int
     */
    public function updateConfig(float $prix, int $remise_pct): bool|int
    {
        return $this->where('id', 1)->update([
            'prix'       => $prix,
            'remise_pct' => $remise_pct,
        ]);
    }

    /**
     * Obtenir le prix final avec remise
     * @param float $basePriceEur
     * @param bool $isGold
     * @return float
     */
    public function applyDiscount(float $basePriceEur, bool $isGold = false): float
    {
        if (!$isGold) {
            return $basePriceEur;
        }

        $config = $this->getActiveConfig();
        if (!$config) {
            return $basePriceEur;
        }

        $discountPercent = $config['remise_pct'];
        $discountedPrice = $basePriceEur * (1 - ($discountPercent / 100));

        return round($discountedPrice, 2);
    }
}
