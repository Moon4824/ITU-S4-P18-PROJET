<?php

namespace App\Controllers;

use App\Models\InterpretationImcModel;
use App\Models\UtilisateurModel;

class ImcController extends BaseController
{
    protected InterpretationImcModel $interpretationModel;

    public function __construct()
    {
        $this->interpretationModel = new InterpretationImcModel();
    }

    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $utilisateurModel = new UtilisateurModel();
        $profil = null;

        $userId = (int) session()->get('user_id');
        if ($userId > 0) {
            $profil = $utilisateurModel->findByIdWithRole($userId);
        }

        $nom = (string) (session()->get('imc_nom') ?: ($profil['nom'] ?? ''));
        $email = (string) (session()->get('imc_email') ?: ($profil['email'] ?? ''));
        $genre = (string) (session()->get('imc_genre') ?: ($profil['genre'] ?? ''));
        $poids = (float) (session()->get('imc_poids') ?: ($profil['poids_actuel'] ?? 0));
        $taille = (float) (session()->get('imc_taille') ?: (((float) ($profil['taille'] ?? 0)) * 100));

        if ($nom === '' || $email === '' || $poids <= 0 || $taille <= 0) {
            return redirect()->to('/register/inscription2');
        }

        $imc = $this->computeImc($poids, $taille);

        return view('user/imc', [
            'title' => 'Inscription - IMC',
            'nom' => $nom,
            'email' => $email,
            'genre' => $genre,
            'poids' => $poids,
            'taille' => $taille,
            'imc' => round($imc, 2),
            'interpretations' => $this->interpretationModel->findAllOrdered(),
            'imcInterpretation' => $this->interpretationModel->findForImc($imc),
        ]);
    }

    /**
     * Return all interpretation ranges as JSON, with added color and css class.
     */
    public function list()
    {
        $items = $this->interpretationModel->findAllOrdered();

        $mapped = array_map(function (array $item): array {
            $item['color'] = $this->colorFor($item['libelle'] ?? null);
            $item['css_class'] = $this->classFor($item['libelle'] ?? null);

            return $item;
        }, $items);

        return $this->response->setJSON($mapped);
    }

    public function calculate()
    {
        $poids = (float) $this->request->getPost('poids');
        $taille = (float) $this->request->getPost('taille');

        if ($poids <= 0 || $taille <= 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'error' => 'Poids et taille doivent être supérieurs à 0.',
            ]);
        }

        $imc = $this->computeImc($poids, $taille);

        return $this->response->setJSON([
            'imc' => round($imc, 2),
            'interpretation' => $this->enrichInterpretation($this->interpretationModel->findForImc($imc)),
        ]);
    }

    protected function enrichInterpretation(?array $interpretation): ?array
    {
        if ($interpretation === null) {
            return null;
        }

        $interpretation['color'] = $this->colorFor($interpretation['libelle'] ?? null);
        $interpretation['css_class'] = $this->classFor($interpretation['libelle'] ?? null);

        return $interpretation;
    }

    protected function computeImc(float $poids, float $tailleCm): float
    {
        $tailleMetres = $tailleCm / 100;

        if ($tailleMetres <= 0) {
            return 0.0;
        }

        return $poids / ($tailleMetres * $tailleMetres);
    }

    protected function colorFor(?string $libelle): string
    {
        $key = $this->normalizeLabel($libelle);

        if (str_contains($key, 'maigreur') || str_contains($key, 'sous')) {
            return '#5bc0de';
        }

        if (str_contains($key, 'normal')) {
            return '#8bc34a';
        }

        if (str_contains($key, 'surpoids')) {
            return '#ffc107';
        }

        return '#f44336';
    }

    protected function classFor(?string $libelle): string
    {
        $key = $this->normalizeLabel($libelle);

        if (str_contains($key, 'maigreur') || str_contains($key, 'sous')) {
            return 'imc-badge-maigreur';
        }

        if (str_contains($key, 'normal')) {
            return 'imc-badge-normal';
        }

        if (str_contains($key, 'surpoids')) {
            return 'imc-badge-surpoids';
        }

        return 'imc-badge-obesite';
    }

    protected function normalizeLabel(?string $label): string
    {
        if ($label === null) {
            return '';
        }

        $normalized = mb_strtolower($label, 'UTF-8');
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $normalized);

        return (string) preg_replace('/[^a-z0-9\s-]/', '', $normalized);
    }
}
