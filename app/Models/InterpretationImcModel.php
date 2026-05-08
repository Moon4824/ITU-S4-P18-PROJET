<?php

namespace App\Models;

use CodeIgniter\Model;

class InterpretationImcModel extends Model
{
    protected $table = 'interpretation_imc';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['libelle', 'min', 'max'];
    protected $useTimestamps = false;

    public function findAllOrdered(): array
    {
        $interpretations = $this->findAll();

        usort($interpretations, static function (array $left, array $right): int {
            $leftMin = $left['min'];
            $rightMin = $right['min'];

            if ($leftMin === null && $rightMin === null) {
                return 0;
            }

            if ($leftMin === null) {
                return -1;
            }

            if ($rightMin === null) {
                return 1;
            }

            return (float) $leftMin <=> (float) $rightMin;
        });

        return $interpretations;
    }

    public function findForImc(float $imc): ?array
    {
        foreach ($this->findAllOrdered() as $interpretation) {
            $min = $interpretation['min'];
            $max = $interpretation['max'];

            if (($min === null || $imc >= (float) $min) && ($max === null || $imc <= (float) $max)) {
                return $interpretation;
            }
        }

        return null;
    }
}