<?php

namespace App\Controllers;

use App\Models\CodeArgentModel;
use App\Models\UtilisateurModel;

class PortefeuilleController extends BaseController
{
    protected UtilisateurModel $utilisateurModel;
    protected CodeArgentModel $codeArgentModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->codeArgentModel = new CodeArgentModel();
    }

    public function summary()
    {
        $user = $this->getAuthenticatedUser();

        if ($user === null) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Session expirée. Veuillez vous reconnecter.',
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'balance' => (float) ($user['solde_monnaie'] ?? 0),
            'balance_label' => $this->formatBalance((float) ($user['solde_monnaie'] ?? 0)),
            'note' => 'Solde actualisé en temps réel.',
        ]);
    }

    public function redeemCode()
    {
        $user = $this->getAuthenticatedUser();

        if ($user === null) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Session expirée. Veuillez vous reconnecter.',
            ]);
        }

        $rules = [
            'code' => 'required|exact_length[15]',
        ];

        $messages = [
            'code' => [
                'required' => 'Veuillez saisir un code.',
                'exact_length' => 'Le code doit contenir exactement 15 caractères.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Le code saisi est invalide.',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $code = strtoupper(trim((string) $this->request->getPost('code')));
        $userId = (int) $user['id'];
        $codeRow = $this->codeArgentModel->findRedeemableCode($code);

        if ($codeRow === null) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Ce code est introuvable, désactivé ou déjà utilisé.',
            ]);
        }

        $amount = (float) ($codeRow['valeur'] ?? 0);

        if ($amount <= 0) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'La valeur du code est invalide.',
            ]);
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $currentUser = $this->utilisateurModel->find($userId);

            if ($currentUser === null) {
                throw new \RuntimeException('Utilisateur introuvable.');
            }

            $newBalance = round(((float) ($currentUser['solde_monnaie'] ?? 0)) + $amount, 2);

            $this->utilisateurModel->update($userId, [
                'solde_monnaie' => $newBalance,
            ]);

            $this->codeArgentModel->markAsUsed((int) $codeRow['id'], $userId);
            $db->table('code_argent_utilisation')->insert([
                'id_code_argent'   => (int) $codeRow['id'],
                'id_utilisateur'   => $userId,
                'montant_credit'   => $amount,
                'date_utilisation' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('La transaction a échoué.');
            }

            $db->transCommit();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Votre compte a été crédité avec succès.',
                'balance' => $newBalance,
                'balance_label' => $this->formatBalance($newBalance),
                'note' => 'Vous pouvez utiliser ce solde pour valider votre régime.',
            ]);
        } catch (\Throwable $exception) {
            $db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Impossible de créditer le compte pour le moment.',
            ]);
        }
    }

    protected function getAuthenticatedUser(): ?array
    {
        if (! session()->get('isLoggedIn')) {
            return null;
        }

        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return null;
        }

        return $this->utilisateurModel->findByIdWithRole($userId);
    }

    protected function formatBalance(float $amount): string
    {
        return number_format($amount, 2, ',', ' ') . ' Ar';
    }
}