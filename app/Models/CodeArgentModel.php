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

    public function findRedeemableCode(string $code, int $userId = 0): ?array
    {
        $codeRow = $this->where('code', $code)
            ->where('est_valide', 1)
            ->first();

        if ($codeRow === null) {
            return null;
        }

        // Si userId est fourni, vérifier que l'utilisateur n'a pas déjà utilisé ce code
        if ($userId > 0) {
            $utilisation = db_connect()
                ->table('code_argent_utilisation')
                ->where('id_code_argent', $codeRow['id'])
                ->where('id_utilisateur', $userId)
                ->get()
                ->getFirstRow('array');

            if ($utilisation !== null) {
                return null; // Utilisateur a déjà utilisé ce code
            }
        }

        return $codeRow;
    }

    public function markAsUsed(int $id, int $userId): bool
    {
        // markAsUsed ne fait rien - la validation se fait via code_argent_utilisation
        // On garde la méthode pour la compatibilité, mais elle ne modifie pas le code
        return true;
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