<?php

namespace App\Controllers;

use App\Models\CodeArgentModel;
use App\Models\GoldConfigModel;
use App\Models\InterpretationImcModel;
use App\Models\RegimeModel;
use App\Models\SportModel;
use App\Models\UtilisateurModel;

class AdminController extends BaseController
{
    public function index()
    {
        $db = db_connect();

        $utilisateurModel = new UtilisateurModel();
        $regimeModel = new RegimeModel();
        $sportModel = new SportModel();
        $codeArgentModel = new CodeArgentModel();
        $interpretationImcModel = new InterpretationImcModel();
        $goldConfigModel = new GoldConfigModel();

        $totalUsers = $utilisateurModel->countAll();
        $goldUsers = $utilisateurModel->where('est_gold', 1)->countAllResults();
        $totalRegimes = $regimeModel->countAll();
        $totalSports = $sportModel->countAll();
        $totalCodes = $codeArgentModel->countAll();
        $activeCodes = $codeArgentModel->where('est_valide', 1)->countAllResults();
        $totalInterpretations = $interpretationImcModel->countAll();
        $totalGoldActivations = $db->table('payments')->where('product', 'GOLD')->countAllResults();

        $goldConfig = $goldConfigModel->getActiveConfig() ?? [
            'prix' => 0,
            'remise_pct' => 0,
            'actif' => 0,
        ];

        $recentGoldActivations = $db->table('payments p')
            ->select('p.created_at, p.product, u.nom AS user_name, u.email AS user_email')
            ->join('utilisateur u', 'u.id = p.user_id', 'left')
            ->where('p.product', 'GOLD')
            ->orderBy('p.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $recentCodeUsages = $db->table('code_argent_utilisation cu')
            ->select('cu.date_utilisation AS created_at, cu.montant_credit, u.nom AS user_name, c.code AS code_value')
            ->join('utilisateur u', 'u.id = cu.id_utilisateur', 'left')
            ->join('code_argent c', 'c.id = cu.id_code_argent', 'left')
            ->orderBy('cu.date_utilisation', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $recentActivity = [];

        foreach ($recentGoldActivations as $activation) {
            $recentActivity[] = [
                'type' => 'Activation Gold',
                'label' => 'Achat Gold',
                'user' => (string) ($activation['user_name'] ?? 'Utilisateur supprimé'),
                'reference' => (string) ($activation['product'] ?? 'GOLD'),
                'date' => (string) ($activation['created_at'] ?? ''),
            ];
        }

        foreach ($recentCodeUsages as $usage) {
            $recentActivity[] = [
                'type' => 'Code argent',
                'label' => 'Crédit ajouté',
                'user' => (string) ($usage['user_name'] ?? 'Utilisateur supprimé'),
                'reference' => (string) ($usage['code_value'] ?? '-'),
                'date' => (string) ($usage['created_at'] ?? ''),
            ];
        }

        usort($recentActivity, static function (array $left, array $right): int {
            return strtotime((string) ($right['date'] ?? '')) <=> strtotime((string) ($left['date'] ?? ''));
        });

        $recentActivity = array_slice($recentActivity, 0, 8);

        $chartBars = [
            ['label' => 'Utilisateurs', 'value' => $totalUsers, 'color' => 'var(--c-primary)'],
            ['label' => 'Gold actifs', 'value' => $goldUsers, 'color' => 'var(--c-warning)'],
            ['label' => 'Régimes', 'value' => $totalRegimes, 'color' => 'var(--c-success)'],
            ['label' => 'Sports', 'value' => $totalSports, 'color' => 'var(--c-info)'],
            ['label' => 'Codes valides', 'value' => $activeCodes, 'color' => 'var(--c-danger)'],
            ['label' => 'IMC', 'value' => $totalInterpretations, 'color' => 'var(--c-muted)'],
        ];

        return view('/admin/dashbord', [
            'title' => 'Dashboard',
            'user'  => [
                'id'      => session()->get('user_id'),
                'role_id' => session()->get('user_role_id'),
                'role'    => session()->get('user_role'),
                'name'    => session()->get('user_name'),
                'email'   => session()->get('user_email'),
            ],
            'stats' => [
                'totalUsers' => $totalUsers,
                'goldUsers' => $goldUsers,
                'totalRegimes' => $totalRegimes,
                'totalSports' => $totalSports,
                'totalCodes' => $totalCodes,
                'activeCodes' => $activeCodes,
                'totalInterpretations' => $totalInterpretations,
                'totalGoldActivations' => $totalGoldActivations,
                'goldConfig' => $goldConfig,
            ],
            'chartBars' => $chartBars,
            'recentActivity' => $recentActivity,
        ]);
    }
    
}
