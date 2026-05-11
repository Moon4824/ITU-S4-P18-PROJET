<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GoldConfigModel;

class GoldController extends BaseController
{
    protected $goldConfigModel;

    public function __construct()
    {
        $this->goldConfigModel = new GoldConfigModel();
    }

    /**
     * Afficher la page de configuration Gold
     */
    public function index()
    {
        $config = $this->goldConfigModel->getActiveConfig();

        return view('admin/gold/index', [
            'config' => $config,
        ]);
    }

    /**
     * Mettre à jour la configuration Gold
     */
    public function update()
    {
        // Vérifier si l'utilisateur est authentifié et admin
        if (!session()->get('user_id') || session()->get('role') !== 'admin') {
            return $this->response->setStatusCode(403)
                ->setJSON(['error' => 'Accès refusé']);
        }

        // Récupérer les données POST
        $prix = $this->request->getPost('prix');
        $remise_pct = $this->request->getPost('remise_pct');

        // Validation
        if (!is_numeric($prix) || $prix <= 0) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'Prix invalide']);
        }

        if (!is_numeric($remise_pct) || $remise_pct < 0 || $remise_pct > 100) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'Pourcentage remise invalide (0-100)']);
        }

        // Mettre à jour
        $success = $this->goldConfigModel->updateConfig((float) $prix, (int) $remise_pct);

        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Configuration Gold mise à jour',
                'prix'    => (float) $prix,
                'remise_pct' => (int) $remise_pct,
            ]);
        } else {
            return $this->response->setStatusCode(500)
                ->setJSON(['error' => 'Erreur lors de la mise à jour']);
        }
    }

    /**
     * API: Obtenir la configuration actuelle
     */
    public function getConfig()
    {
        $config = $this->goldConfigModel->getActiveConfig();

        if (!$config) {
            return $this->response->setStatusCode(404)
                ->setJSON(['error' => 'Configuration Gold non trouvée']);
        }

        return $this->response->setJSON($config);
    }
}
