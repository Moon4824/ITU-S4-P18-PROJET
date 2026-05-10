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

    public function findRedeemableCode(string $code): ?array
    {
        return $this->where('code', $code)
            ->where('est_valide', 1)
            ->groupStart()
                ->where('id_utilisateur', null)
                ->orWhere('id_utilisateur', 0)
            ->groupEnd()
            ->first();
    }

    public function markAsUsed(int $id, int $userId): bool
    {
        return (bool) $this->update($id, [
            'est_valide' => 0,
            'id_utilisateur' => $userId,
        ]);
    }

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