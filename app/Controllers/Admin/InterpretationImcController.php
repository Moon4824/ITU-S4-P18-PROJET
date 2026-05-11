<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InterpretationImcModel;

class InterpretationImcController extends BaseController
{
    protected InterpretationImcModel $interpretationModel;

    public function __construct()
    {
        $this->interpretationModel = new InterpretationImcModel();
    }

    public function index(): string
    {
        $keyword = $this->request->getGet('q');

        $builder = $this->interpretationModel->orderBy('min', 'ASC');

        if ($keyword) {
            $builder->groupStart()
                ->like('libelle', $keyword)
                ->orLike('min', $keyword)
                ->orLike('max', $keyword)
                ->groupEnd();
        }

        return view('admin/interpretation_imc/index', [
            'title' => 'Interprétations IMC',
            'subtitle' => 'Gestion des tranches et libellés IMC',
            'interpretations' => $builder->paginate(15),
            'pager' => $this->interpretationModel->pager,
            'keyword' => $keyword,
        ]);
    }

    public function create(): string
    {
        return view('admin/interpretation_imc/create', [
            'title' => 'Ajouter une interprétation IMC',
            'subtitle' => 'Nouvelle tranche d\'interprétation',
        ]);
    }

    public function store()
    {
        $rules = [
            'libelle' => 'required|min_length[2]|max_length[100]|is_unique[interpretation_imc.libelle]',
            'min' => 'permit_empty|decimal',
            'max' => 'permit_empty|decimal',
        ];

        $messages = [
            'libelle' => [
                'required' => 'Le libellé est obligatoire.',
                'is_unique' => 'Ce libellé existe déjà.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->interpretationModel->insert([
            'libelle' => $this->request->getPost('libelle'),
            'min' => $this->emptyToNull($this->request->getPost('min')),
            'max' => $this->emptyToNull($this->request->getPost('max')),
        ]);

        return redirect()->to('/admin/imc/interpretations')->with('success', 'Interprétation IMC ajoutée avec succès.');
    }

    public function edit(int $id): string
    {
        $interpretation = $this->interpretationModel->find($id);

        if (! $interpretation) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Interprétation IMC #$id introuvable.");
        }

        return view('admin/interpretation_imc/edit', [
            'title' => 'Modifier l\'interprétation IMC',
            'subtitle' => $interpretation['libelle'],
            'interpretation' => $interpretation,
        ]);
    }

    public function update(int $id)
    {
        $interpretation = $this->interpretationModel->find($id);

        if (! $interpretation) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Interprétation IMC #$id introuvable.");
        }

        $rules = [
            'libelle' => "required|min_length[2]|max_length[100]|is_unique[interpretation_imc.libelle,id,{$id}]",
            'min' => 'permit_empty|decimal',
            'max' => 'permit_empty|decimal',
        ];

        $messages = [
            'libelle' => [
                'required' => 'Le libellé est obligatoire.',
                'is_unique' => 'Ce libellé est déjà utilisé.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->interpretationModel->update($id, [
            'libelle' => $this->request->getPost('libelle'),
            'min' => $this->emptyToNull($this->request->getPost('min')),
            'max' => $this->emptyToNull($this->request->getPost('max')),
        ]);

        return redirect()->to('/admin/imc/interpretations')->with('success', 'Interprétation IMC mise à jour avec succès.');
    }

    public function delete(int $id)
    {
        $interpretation = $this->interpretationModel->find($id);

        if (! $interpretation) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Interprétation IMC #$id introuvable.");
        }

        $this->interpretationModel->delete($id);

        return redirect()->to('/admin/imc/interpretations')->with('success', 'Interprétation IMC supprimée avec succès.');
    }

    private function emptyToNull(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }
}