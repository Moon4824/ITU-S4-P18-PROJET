<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model pour la table `utilisateur`
 */
class UtilisateurModel extends Model
{
    protected $table      = 'utilisateur';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $allowedFields  = [
        'id_role',
        'nom',
        'email',
        'mot_de_passe',
        'date_naissance',
        'genre',
        'poids_actuel',
        'taille',
        'est_gold',
        'solde_monnaie',
    ];

    protected $useTimestamps = false;

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    public function findByIdWithRole(int $id): ?array
    {
        return $this->select('utilisateur.*, user_role.role AS role_label')
            ->join('user_role', 'user_role.id = utilisateur.id_role', 'left')
            ->where('utilisateur.id', $id)
            ->first();
    }

    public function findByEmailWithRole(string $email): ?array
    {
        return $this->select('utilisateur.*, user_role.role AS role_label')
            ->join('user_role', 'user_role.id = utilisateur.id_role', 'left')
            ->where('utilisateur.email', $email)
            ->first();
    }

    public function verifyLogin(string $email, string $password): ?array
    {
        $user = $this->findByEmailWithRole($email);

        if ($user === null) {
            return null;
        }

        if (! password_verify($password, $user['mot_de_passe'])) {
            return null;
        }

        return [
            'id'             => (int) $user['id'],
            'id_role'        => (int) $user['id_role'],
            'nom'            => $user['nom'],
            'email'          => $user['email'],
            'role'           => $user['role_label'] ?? null,
            'est_gold'       => (int) $user['est_gold'],
            'solde_monnaie'  => (float) $user['solde_monnaie'],
        ];
    }

    public function getRoleByUserId(int $id): ?array
    {
        return $this->select('user_role.id AS role_id, user_role.role AS role_label')
            ->join('user_role', 'user_role.id = utilisateur.id_role', 'left')
            ->where('utilisateur.id', $id)
            ->first();
    }

    public function buildLoginSessionData(array $user): array
    {
        return [
            'isLoggedIn'    => true,
            'user_id'       => $user['id'] ?? null,
            'user_role_id'  => $user['id_role'] ?? null,
            'user_role'     => $user['role'] ?? null,
            'user_name'     => $user['nom'] ?? null,
            'user_email'    => $user['email'] ?? null,
        ];
    }

    public function getAdminLoginData(): ?array
    {
        return $this->select('utilisateur.id, utilisateur.nom, utilisateur.email, user_role.role AS role_label')
            ->join('user_role', 'user_role.id = utilisateur.id_role', 'left')
            ->where('user_role.role', 'admin')
            ->first();
    }

    // Note: la clé étrangère `id_role` est gérée au niveau de la BDD.
}
