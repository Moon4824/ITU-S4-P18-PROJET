<?php

namespace App\Models;

use CodeIgniter\Model;

class InterpretationImcModel extends Model
{
    protected $table      = 'interpretation_imc';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'min', 'max'];

    /**
     * Récupère l'interprétation selon le score IMC
     */
    public function getInterpretation($imc)
    {
        return $this->where('min <=', $imc)
                    ->groupStart()
                        ->where('max >=', $imc)
                        ->orWhere('max', null)
                    ->groupEnd()
                    ->first();
    }
}