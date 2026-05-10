<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CodeArgentModel;

class CodeArgentController extends BaseController
{
    protected CodeArgentModel $codeModel;

    public function __construct()
    {
        $this->codeModel = new CodeArgentModel();
    }

    // ─────────────────────────────────────────────
    // LIST  –  GET /admin/codes
    // ─────────────────────────────────────────────
    public function index(): string
    {
        $keyword = $this->request->getGet('q');
        $statut  = $this->request->getGet('statut'); // 'valide' | 'invalide'

        $builder = $this->codeModel->orderBy('id', 'DESC');

        if ($keyword) {
            $builder->like('code', $keyword);
        }

        if ($statut === 'valide') {
            $builder->where('est_valide', 1);
        } elseif ($statut === 'invalide') {
            $builder->where('est_valide', 0);
        }

        return view('admin/codes/index', [
            'title'    => 'Codes argent',
            'subtitle' => 'Gestion des codes de recharge',
            'codes'    => $builder->paginate(15),
            'pager'    => $this->codeModel->pager,
            'keyword'  => $keyword,
            'statut'   => $statut,
        ]);
    }

    // ─────────────────────────────────────────────
    // CREATE  –  GET /admin/codes/create
    // ─────────────────────────────────────────────
    public function create(): string
    {
        return view('admin/codes/create', [
            'title'    => 'Ajouter un code',
            'subtitle' => 'Nouveau code de recharge',
        ]);
    }

    // ─────────────────────────────────────────────
    // STORE  –  POST /admin/codes/store
    // ─────────────────────────────────────────────
    public function store()
    {
        $rules = [
            'code'   => 'required|exact_length[15]|is_unique[code_argent.code]',
            'valeur' => 'required|decimal|greater_than[0]',
        ];

        $messages = [
            'code' => [
                'required'     => 'Le code est obligatoire.',
                'exact_length' => 'Le code doit contenir exactement 15 caractères.',
                'is_unique'    => 'Ce code existe déjà.',
            ],
            'valeur' => [
                'required'     => 'La valeur est obligatoire.',
                'decimal'      => 'La valeur doit être un nombre.',
                'greater_than' => 'La valeur doit être supérieure à 0.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->codeModel->insert([
            'code'           => $this->request->getPost('code'),
            'valeur'         => (float) $this->request->getPost('valeur'),
            'est_valide'     => 1,
            'id_utilisateur' => null,
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code créé avec succès.');
    }

    // ─────────────────────────────────────────────
    // SHOW  –  GET /admin/codes/show/{id}
    // ─────────────────────────────────────────────
    public function show(int $id): string
    {
        $code = $this->codeModel->find($id);

        if (! $code) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Code #$id introuvable.");
        }

        return view('admin/codes/show', [
            'title'    => 'Détail du code',
            'subtitle' => $code['code'],
            'code'     => $code,
        ]);
    }

    // ─────────────────────────────────────────────
    // EDIT  –  GET /admin/codes/edit/{id}
    // ─────────────────────────────────────────────
    public function edit(int $id): string
    {
        $code = $this->codeModel->find($id);

        if (! $code) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Code #$id introuvable.");
        }

        return view('admin/codes/edit', [
            'title'    => 'Modifier le code',
            'subtitle' => $code['code'],
            'code'     => $code,
        ]);
    }

    // ─────────────────────────────────────────────
    // UPDATE  –  POST /admin/codes/update/{id}
    // ─────────────────────────────────────────────
    public function update(int $id)
    {
        $code = $this->codeModel->find($id);

        if (! $code) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Code #$id introuvable.");
        }

        $rules = [
            'code'   => "required|exact_length[15]|is_unique[code_argent.code,id,{$id}]",
            'valeur' => 'required|decimal|greater_than[0]',
        ];

        $messages = [
            'code' => [
                'required'     => 'Le code est obligatoire.',
                'exact_length' => 'Le code doit contenir exactement 15 caractères.',
                'is_unique'    => 'Ce code est déjà utilisé par un autre enregistrement.',
            ],
            'valeur' => [
                'required'     => 'La valeur est obligatoire.',
                'greater_than' => 'La valeur doit être supérieure à 0.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->codeModel->update($id, [
            'code'   => $this->request->getPost('code'),
            'valeur' => (float) $this->request->getPost('valeur'),
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code mis à jour avec succès.');
    }

    // ─────────────────────────────────────────────
    // TOGGLE  –  GET /admin/codes/toggle/{id}
    // ─────────────────────────────────────────────
    public function toggle(int $id)
    {
        $code = $this->codeModel->find($id);

        if (! $code) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Code #$id introuvable.");
        }

        $this->codeModel->toggleValide($id);

        $msg = (int) $code['est_valide'] === 1 ? 'Code désactivé.' : 'Code activé.';

        return redirect()->to('/admin/codes')->with('success', $msg);
    }

    // ─────────────────────────────────────────────
    // DELETE  –  GET /admin/codes/delete/{id}
    // ─────────────────────────────────────────────
    public function delete(int $id)
    {
        $code = $this->codeModel->find($id);

        if (! $code) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Code #$id introuvable.");
        }

        $this->codeModel->delete($id);

        return redirect()->to('/admin/codes')->with('success', 'Code supprimé avec succès.');
    }
}