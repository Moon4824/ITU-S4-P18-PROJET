<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\RegimeDetailModel;
use App\Models\TypeObjectifModel;
use App\Models\UtilisateurModel;

class ObjectifController extends BaseController
{
    protected TypeObjectifModel $typeObjectifModel;
    protected UtilisateurModel $utilisateurModel;
    protected RegimeDetailModel $regimeDetailModel;
    protected ObjectifModel $objectifModel;

    public function __construct()
    {
        $this->typeObjectifModel = new TypeObjectifModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->regimeDetailModel = new RegimeDetailModel();
        $this->objectifModel = new ObjectifModel();
    }

    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        return redirect()->to('/objectifs/choose');
    }

    public function choose()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user = $this->getCurrentUser();

        if ($user === null) {
            return redirect()->to('/auth/login');
        }

        $existingObjective = $this->objectifModel->findLatestByUser((int) $user['id']);

        if ($existingObjective !== null) {
            $this->syncObjectiveSession($existingObjective);

            return redirect()->to('/user')->with('info', 'Vous avez déjà un objectif enregistré.');
        }

        $data = $this->buildChooseViewData($user);

        if (strtolower($this->request->getMethod()) !== 'post') {
            return view('user/objectifs/index', $data);
        }

        $rules = [
            'id_type_objectif' => 'required|integer',
            'poids_objectif'    => 'required|decimal|greater_than[0]',
            'date_debut'        => 'required|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return view('user/objectifs/index', $this->buildChooseViewData($user, [
                'step' => 1,
                'old' => $this->request->getPost(),
                'validationErrors' => $this->validator ? $this->validator->getErrors() : [],
                'errorMessage' => 'Merci de corriger les champs du formulaire.',
            ]));
        }

        $objectiveId = (int) $this->request->getPost('id_type_objectif');
        $objective = $this->typeObjectifModel->find($objectiveId);

        if ($objective === null) {
            return view('user/objectifs/index', $this->buildChooseViewData($user, [
                'step' => 1,
                'old' => $this->request->getPost(),
                'errorMessage' => 'Le type d\'objectif sélectionné est invalide.',
            ]));
        }

        $poidsActuel = (float) $user['poids_actuel'];
        $taille = (float) $user['taille'];
        $poidsObjectif = (float) $this->request->getPost('poids_objectif');

        if ($this->isImcIdealObjective((string) $objective['libelle'])) {
            $poidsObjectif = $this->calculateIdealWeight($taille);
        }

        $diffPoids = $poidsObjectif - $poidsActuel;
        $suggestions = $this->regimeDetailModel->getSuggestions($diffPoids);

        if (empty($suggestions)) {
            return view('user/objectifs/index', $this->buildChooseViewData($user, [
                'step' => 1,
                'old' => $this->request->getPost(),
                'errorMessage' => 'Aucun régime compatible avec cet objectif.',
            ]));
        }

        return view('user/objectifs/index', $this->buildChooseViewData($user, [
            'step' => 2,
            'selectedObjective' => $objective,
            'poidsObjectif' => $poidsObjectif,
            'poidsActuel' => $poidsActuel,
            'taille' => $taille,
            'diffPoids' => $diffPoids,
            'suggestions' => $suggestions,
            'old' => $this->request->getPost(),
        ]));
    }

    public function save()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user = $this->getCurrentUser();

        if ($user === null) {
            return redirect()->to('/auth/login');
        }

        if ($this->objectifModel->findLatestByUser((int) $user['id']) !== null) {
            return redirect()->to('/user')->with('info', 'Vous avez déjà un objectif enregistré.');
        }

        $rules = [
            'id_type_objectif' => 'required|integer',
            'poids_objectif'   => 'required|decimal|greater_than[0]',
            'regime_id'        => 'required|integer',
            'date_debut'       => 'required|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez vérifier les informations du régime choisi.');
        }

        $objective = $this->typeObjectifModel->find((int) $this->request->getPost('id_type_objectif'));

        if ($objective === null) {
            return redirect()->back()->withInput()->with('error', 'Le type d\'objectif sélectionné est invalide.');
        }

        $poidsActuel = (float) $user['poids_actuel'];
        $taille = (float) $user['taille'];
        $poidsObjectif = (float) $this->request->getPost('poids_objectif');

        if ($this->isImcIdealObjective((string) $objective['libelle'])) {
            $poidsObjectif = $this->calculateIdealWeight($taille);
        }

        $diffPoids = $poidsObjectif - $poidsActuel;

        if ((float) $diffPoids === 0.0) {
            return redirect()->back()->withInput()->with('error', 'Le poids objectif doit être différent du poids actuel.');
        }

        $suggestions = $this->regimeDetailModel->getSuggestions($diffPoids);
        $selectedRegimeId = (int) $this->request->getPost('regime_id');
        $selectedSuggestion = null;

        foreach ($suggestions as $suggestion) {
            if ((int) ($suggestion['regime_id'] ?? 0) === $selectedRegimeId) {
                $selectedSuggestion = $suggestion;
                break;
            }
        }

        if ($selectedSuggestion === null) {
            return redirect()->back()->withInput()->with('error', 'Le régime sélectionné ne correspond pas à l\'objectif calculé.');
        }

        $prixTotal = (float) $selectedSuggestion['prix_total_calcule'];

        if ((int) $user['est_gold'] === 1) {
            $prixTotal = round($prixTotal * 0.85, 2);
        }

        if ((float) $user['solde_monnaie'] < $prixTotal) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour valider ce régime.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->objectifModel->insert([
            'id_utilisateur'   => (int) $user['id'],
            'id_type_objectif' => (int) $objective['id'],
            'poids_initial'    => $poidsActuel,
            'objectif_poids'   => $poidsObjectif,
            'regime_id'        => $selectedRegimeId,
            'sport_id'         => null,
            'IMC_initial'      => $taille > 0 ? round($poidsActuel / ($taille * $taille), 2) : null,
            'duree_objectif'   => (int) $selectedSuggestion['duree_totale_calculee'],
            'prix_total'       => $prixTotal,
            'date_debut'       => $this->request->getPost('date_debut'),
        ]);

        $this->utilisateurModel->update((int) $user['id'], [
            'solde_monnaie' => round(((float) $user['solde_monnaie']) - $prixTotal, 2),
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'enregistrement de l\'objectif.');
        }

        $savedObjective = $this->objectifModel->findLatestByUser((int) $user['id']);

        if ($savedObjective !== null) {
            $this->syncObjectiveSession($savedObjective);
        }

        return redirect()->to('/user')->with('success', 'Objectif et régime enregistrés avec succès.');
    }

    private function getCurrentUser(): ?array
    {
        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return null;
        }

        return $this->utilisateurModel->findByIdWithRole($userId);
    }

    private function buildChooseViewData(array $user, array $extra = []): array
    {
        $poidsActuel = (float) ($user['poids_actuel'] ?? 0);
        $taille = (float) ($user['taille'] ?? 0);
        $imcActuel = $taille > 0 ? round($poidsActuel / ($taille * $taille), 2) : null;

        return array_merge([
            'title' => 'Choisir mes objectifs',
            'user' => [
                'id' => $user['id'] ?? null,
                'role' => $user['role_label'] ?? ($user['role'] ?? null),
                'name' => $user['nom'] ?? ($user['name'] ?? null),
                'email' => $user['email'] ?? null,
            ],
            'objectifs' => $this->typeObjectifModel->findAllOrdered(),
            'step' => 1,
            'poidsActuel' => $poidsActuel,
            'taille' => $taille,
            'imcActuel' => $imcActuel,
            'poidsIdeal' => $taille > 0 ? $this->calculateIdealWeight($taille) : null,
            'suggestions' => [],
            'selectedObjective' => null,
            'errorMessage' => null,
            'old' => [],
            'errors' => null,
        ], $extra);
    }

    private function syncObjectiveSession(array $objective): void
    {
        session()->set([
            'selected_objective_id' => (int) ($objective['id_type_objectif'] ?? 0),
            'selected_objective_label' => (string) ($objective['type_objectif_label'] ?? ''),
        ]);
    }

    private function calculateIdealWeight(float $taille): float
    {
        return round(22 * $taille * $taille, 2);
    }

    private function isImcIdealObjective(string $label): bool
    {
        $normalized = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $label) ?: $label);

        return str_contains($normalized, 'imc') && str_contains($normalized, 'ideal');
    }
}
