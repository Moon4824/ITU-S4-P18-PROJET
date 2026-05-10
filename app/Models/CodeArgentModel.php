<?php

namespace App\Models;

use CodeIgniter\Model;

class CodeArgentModel extends Model
{
    protected $table         = 'code_argent';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['code', 'valeur', 'est_valide', 'id_utilisateur'];
    protected $useTimestamps = false;

    // ── Toggle est_valide ─────────────────────────────────────
    public function toggleValide(int $id): bool
    {
        $row = $this->find($id);
        if (! $row) return false;

        return (bool) $this->update($id, [
            'est_valide' => (int) $row['est_valide'] === 1 ? 0 : 1,
        ]);
    }
}