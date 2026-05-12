<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\SportModel;
use App\Models\RegimeDetailModel;
use App\Models\TypeObjectifModel;
use App\Models\UtilisateurModel;
use App\Libraries\SimplePdf;
use App\Models\GoldConfigModel;

class ObjectifController extends BaseController
{
    protected TypeObjectifModel $typeObjectifModel;
    protected UtilisateurModel $utilisateurModel;
    protected RegimeDetailModel $regimeDetailModel;
    protected SportModel $sportModel;
    protected ObjectifModel $objectifModel;

    public function __construct()
    {
        $this->typeObjectifModel = new TypeObjectifModel();
        $this->utilisateurModel = new UtilisateurModel();
        $this->regimeDetailModel = new RegimeDetailModel();
        $this->sportModel = new SportModel();
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

        $rules = [
            'id_type_objectif' => 'required|integer',
            'poids_objectif'   => 'required|decimal|greater_than[0]',
            'regime_id'        => 'required|integer',
            'sport_id'         => 'required|integer',
            'date_debut'       => 'required|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez vérifier les informations du régime choisi.');
        }

        $objective = $this->typeObjectifModel->find((int) $this->request->getPost('id_type_objectif'));

        if ($objective === null) {
            return redirect()->back()->withInput()->with('error', 'Le type d\'objectif sélectionné est invalide.');
        }

        $sportId = (int) $this->request->getPost('sport_id');
        $sport = $this->sportModel->find($sportId);

        if ($sport === null) {
            return redirect()->back()->withInput()->with('error', 'Le sport sélectionné est invalide.');
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
            return view('user/objectifs/index', $this->buildChooseViewData($user, [
                'step' => 2,
                'selectedObjective' => $objective,
                'poidsObjectif' => $poidsObjectif,
                'poidsActuel' => $poidsActuel,
                'taille' => $taille,
                'diffPoids' => $diffPoids,
                'suggestions' => $suggestions,
                'old' => $this->request->getPost(),
                'selectedRegimeId' => $selectedRegimeId,
                'openWalletModal' => true,
                'errorMessage' => 'Solde insuffisant pour valider ce régime. entrer une code pour recharger votre solde',
            ]));
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->objectifModel->insert([
            'id_utilisateur'   => (int) $user['id'],
            'id_type_objectif' => (int) $objective['id'],
            'poids_initial'    => $poidsActuel,
            'objectif_poids'   => $poidsObjectif,
            'regime_id'        => $selectedRegimeId,
            'sport_id'         => $sportId,
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

    public function exportPdf()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $user = $this->getCurrentUser();

        if ($user === null) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'id_type_objectif' => 'required|integer',
            'poids_objectif'   => 'required|decimal|greater_than[0]',
            'regime_id'        => 'required|integer',
            'sport_id'         => 'required|integer',
            'date_debut'       => 'required|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez vérifier les informations du détail à exporter.');
        }

        $objective = $this->typeObjectifModel->find((int) $this->request->getPost('id_type_objectif'));

        if ($objective === null) {
            return redirect()->back()->withInput()->with('error', 'Le type d\'objectif sélectionné est invalide.');
        }

        $sportId = (int) $this->request->getPost('sport_id');
        $sport = $this->sportModel->find($sportId);

        if ($sport === null) {
            return redirect()->back()->withInput()->with('error', 'Le sport sélectionné est invalide.');
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

        $pdf = new SimplePdf();
        $pdf->download(
            'detail-objectif-' . (int) $user['id'] . '.pdf',
            $this->buildPdfLines($user, $objective, $sport, $selectedSuggestion, $poidsActuel, $poidsObjectif, $diffPoids)
        );

        return redirect()->to('/objectifs/choose');
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

        $data = array_merge([
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
            'sports' => $this->sportModel->findAllOrdered(),
            'suggestions' => [],
            'selectedObjective' => null,
            'errorMessage' => null,
            'old' => [],
            'errors' => null,
        ], $extra);

        // Si des suggestions existent et que l'utilisateur est GOLD,
        // appliquer la remise configurée aux prix totaux affichés.
        if (! empty($data['suggestions']) && ((int) ($user['est_gold'] ?? 0) === 1)) {
            $goldModel = new GoldConfigModel();
            $config = $goldModel->getActiveConfig();
            $remisePct = (int) ($config['remise_pct'] ?? 0);

            foreach ($data['suggestions'] as &$sugg) {
                $original = (float) ($sugg['prix_total_calcule'] ?? 0);
                $discounted = round($original * (1 - ($remisePct / 100)), 2);
                $sugg['prix_total_calcule'] = $discounted;
                $sugg['prix_total_original'] = $original;
            }
            unset($sugg);
        }

        return $data;
    }

    private function syncObjectiveSession(array $objective): void
    {
        session()->set([
            'selected_objective_id' => (int) ($objective['id_type_objectif'] ?? 0),
            'selected_objective_label' => (string) ($objective['type_objectif_label'] ?? ''),
            'selected_sport_label' => (string) ($objective['sport_label'] ?? ''),
        ]);
    }

    private function calculateIdealWeight(float $taille): float
    {
        return round(22 * $taille * $taille, 2);
    }

    private function buildPdfLines(array $user, array $objective, array $sport, array $selectedSuggestion, float $poidsActuel, float $poidsObjectif, float $diffPoids): array
    {
        $generatedAt = date('Y-m-d H:i');

        return [
            'NUTRISTEP',
            'DETAIL DE L OBJECTIF',
            'Export genere le ' . $generatedAt,
            '',
            '============================================================',
            'Utilisateur        : ' . (string) ($user['nom'] ?? ''),
            'Type d objectif    : ' . (string) ($objective['libelle'] ?? ''),
            'Date de debut      : ' . (string) $this->request->getPost('date_debut'),
            '',
            '--- RECAPITULATIF POIDS -----------------------------------',
            'Poids actuel      : ' . number_format($poidsActuel, 2, ',', ' ') . ' kg',
            'Poids objectif    : ' . number_format($poidsObjectif, 2, ',', ' ') . ' kg',
            'Difference        : ' . number_format($diffPoids, 2, ',', ' ') . ' kg',
            '',
            '--- SPORT CHOISI -----------------------------------------',
            'Sport             : ' . (string) ($sport['nom'] ?? ''),
            '',
            '--- REGIME SELECTIONNE -----------------------------------',
            'Nom               : ' . (string) ($selectedSuggestion['nom'] ?? ''),
            'Duree de base     : ' . (string) ($selectedSuggestion['duree'] ?? '') . ' jours',
            'Variation de poids: ' . number_format((float) ($selectedSuggestion['variation_poids'] ?? 0), 2, ',', ' '),
            'Duree totale      : ' . (string) ($selectedSuggestion['duree_totale_calculee'] ?? 0) . ' jours',
            'Prix journalier   : ' . number_format((float) ($selectedSuggestion['prix'] ?? 0), 2, ',', ' ') . ' Ar',
            'Prix total        : ' . number_format((float) ($selectedSuggestion['prix_total_calcule'] ?? 0), 2, ',', ' ') . ' Ar',
            '',
            '--- REPARTITION DU REGIME --------------------------------',
            'Viande            : ' . (string) ($selectedSuggestion['pct_viande'] ?? 0) . ' %',
            'Poisson           : ' . (string) ($selectedSuggestion['pct_poisson'] ?? 0) . ' %',
            'Volaille          : ' . (string) ($selectedSuggestion['pct_volaille'] ?? 0) . ' %',
        ];
    }

    private function isImcIdealObjective(string $label): bool
    {
        $normalized = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $label) ?: $label);

        return str_contains($normalized, 'imc') && str_contains($normalized, 'ideal');
    }
}
