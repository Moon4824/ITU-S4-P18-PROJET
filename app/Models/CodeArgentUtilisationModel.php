<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeArgentUtilisationModel extends Model
{
    protected $table         = 'code_argent_utilisation';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['id_code_argent', 'id_utilisateur', 'montant_credit', 'date_utilisation'];
    protected $useTimestamps = false;

    public function recordUsage(int $codeId, int $userId, float $amount): bool
    {
        return (bool) $this->insert([
            'id_code_argent'   => $codeId,
            'id_utilisateur'   => $userId,
            'montant_credit'   => $amount,
            'date_utilisation' => date('Y-m-d H:i:s'),
        ]);
    }
}