<?php

namespace App\Controllers;

use App\Models\CodeArgentModel;
use App\Models\UtilisateurModel;
use App\Models\GoldConfigModel;

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

        $goldModel = new GoldConfigModel();
        $goldConfig = $goldModel->getActiveConfig();

        return $this->response->setJSON([
            'success' => true,
            'balance' => (float) ($user['solde_monnaie'] ?? 0),
            'balance_label' => $this->formatBalance((float) ($user['solde_monnaie'] ?? 0)),
            'est_gold' => (int) ($user['est_gold'] ?? 0),
            'gold_price' => $goldConfig ? (float) ($goldConfig['prix'] ?? 0) : 0,
            'gold_discount' => $goldConfig ? (int) ($goldConfig['remise_pct'] ?? 0) : 0,
            'note' => 'Solde actualisé en temps réel.',
        ]);
    }

    /**
     * Activer l'option Gold pour l'utilisateur authentifié.
     * Cette action déduit le prix du Gold du solde, marque `est_gold = 1` et enregistre dans `payments`.
     */
    public function activateGold()
    {
        $user = $this->getAuthenticatedUser();

        if ($user === null) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Session expirée. Veuillez vous reconnecter.',
            ]);
        }

        $userId = (int) $user['id'];

        if ((int) ($user['est_gold'] ?? 0) === 1) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Vous avez déjà l\'option Gold.',
                'est_gold' => 1,
            ]);
        }

        $goldModel = new GoldConfigModel();
        $config = $goldModel->getActiveConfig();

        if (!$config) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'La configuration Gold n\'est pas disponible.',
            ]);
        }

        $goldPrice = (float) ($config['prix'] ?? 0);
        $userBalance = (float) ($user['solde_monnaie'] ?? 0);

        if ($userBalance < $goldPrice) {
            return $this->response->setStatusCode(422)->setJSON([
                'success' => false,
                'message' => 'Solde insuffisant pour activer l\'option Gold.',
                'required' => number_format($goldPrice, 2, ',', ' '),
                'available' => number_format($userBalance, 2, ',', ' '),
            ]);
        }

        $db = db_connect();
        $db->transBegin();

        try {
            $newBalance = round($userBalance - $goldPrice, 2);
            $this->utilisateurModel->update($userId, [
                'est_gold' => 1,
                'solde_monnaie' => $newBalance,
            ]);

            // Enregistrer un paiement simple (produit GOLD)
            $db->table('payments')->insert([
                'user_id' => $userId,
                'product' => 'GOLD',
                'amount' => $goldPrice,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($db->transStatus() === false) {
                throw new \RuntimeException('La transaction a échoué.');
            }

            $db->transCommit();

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Option Gold activée avec succès.',
                'est_gold' => 1,
                'new_balance' => $newBalance,
                'new_balance_label' => $this->formatBalance($newBalance),
            ]);
        } catch (\Throwable $exception) {
            $db->transRollback();

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Impossible d\'activer Gold pour le moment.',
            ]);
        }
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
        $codeRow = $this->codeArgentModel->findRedeemableCode($code, $userId);

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