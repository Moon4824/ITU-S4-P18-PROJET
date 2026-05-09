<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\RegimeDetailModel;

class RegimeController extends BaseController
{
    protected $regimeModel;
    protected $detailModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
        $this->detailModel = new RegimeDetailModel();
    }

    // Liste tous les régimes
    public function index()
    {
        $data['regimes'] = $this->regimeModel->getAllDetails();
        return view('admin/regime/index', $data);
    }

    // Formulaire de création
    public function create()
    {
        return view('admin/regime/form', [
            'regime' => null,
            'detail' => null,
        ]);
    }

    public function modif()
    {
        $regime = $this->regimeModel->find($this->request->getPost('id'));
        $detail = $regime ? $this->detailModel->where('id_regime', $regime['id'])->first() : null;

        return view('admin/regime/form', [
            'regime' => $regime,
            'detail' => $detail,
        ]);
    }

    // Enregistrement (Insert)
    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Début de la transaction

        // 1. Enregistrement du régime (Table principale)
        $idRegime = $this->regimeModel->insert([
            'nom'          => $this->request->getPost('nom'),
            'pct_viande'   => $this->request->getPost('pct_viande'),
            'pct_poisson'  => $this->request->getPost('pct_poisson'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
        ]);

        // 2. Enregistrement du détail (Table liée)
        $this->detailModel->insert([
            'id_regime'       => $idRegime,
            'duree'           => $this->request->getPost('duree'),
            'prix'            => $this->request->getPost('prix'),
            'variation_poids' => $this->request->getPost('variation_poids'),
        ]);

        $db->transComplete(); // Fin de la transaction

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erreur lors de la création.');
        }

        return redirect()->to('/admin/regime')->with('success', 'Régime créé avec succès.');
    }

    // Formulaire d'édition
    public function edit($id)
    {
        $data['regime'] = $this->regimeModel->find($id);
        // On récupère le premier détail associé
        $data['detail'] = $this->detailModel->where('id_regime', $id)->first();

        if (!$data['regime']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/regime/form', $data);
    }

    // Mise à jour (Update)
    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Update du régime
        $this->regimeModel->update($id, [
            'nom'          => $this->request->getPost('nom'),
            'pct_viande'   => $this->request->getPost('pct_viande'),
            'pct_poisson'  => $this->request->getPost('pct_poisson'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
        ]);

        // 2. Update du détail (on cherche l'ID du détail lié à ce régime)
        $detail = $this->detailModel->where('id_regime', $id)->first();
        $this->detailModel->update($detail['id'], [
            'duree'           => $this->request->getPost('duree'),
            'prix'            => $this->request->getPost('prix'),
            'variation_poids' => $this->request->getPost('variation_poids'),
        ]);

        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour.');
        }

        return redirect()->to('/admin/regime')->with('success', 'Mise à jour effectuée.');
    }

    // Suppression (Delete)
    public function delete($id)
    {
        // Grâce aux clés étrangères (ON DELETE CASCADE), 
        // supprimer le régime supprimera automatiquement les détails en SQL.
        $this->regimeModel->delete($id);
        return redirect()->to('/admin/regime')->with('success', 'Régime supprimé.');
    }
}