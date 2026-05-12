<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SportModel;

class SportController extends BaseController
{
    protected SportModel $sportModel;

    public function __construct()
    {
        $this->sportModel = new SportModel();
    }

    // ─────────────────────────────────────────────
    // LIST  –  GET /admin/sports
    // ─────────────────────────────────────────────
    public function index(): string
    {
        $keyword = $this->request->getGet('q');

        $builder = $this->sportModel->orderBy('nom', 'ASC');

        if ($keyword) {
            $builder->like('nom', $keyword);
        }

        $data = [
            'title'   => 'Liste des sports',
            'sports'  => $builder->paginate(10),
            'pager'   => $this->sportModel->pager,
            'keyword' => $keyword,
        ];

        return view('admin/sport/index', $data);
    }

    // ─────────────────────────────────────────────
    // CREATE FORM  –  GET /admin/sports/create
    // ─────────────────────────────────────────────
    public function create(): string
    {
        return view('admin/sport/create', [
            'title'      => 'Ajouter un sport',
            'validation' => \Config\Services::validation(),
        ]);
    }

    // ─────────────────────────────────────────────
    // STORE  –  POST /admin/sports/store
    // ─────────────────────────────────────────────
    public function store()
    {
        $rules = [
            'nom'          => 'required|min_length[2]|max_length[150]|is_unique[sport.nom]',
            'apport_poids' => 'required|in_list[-1,0,1]',
        ];

        $messages = [
            'nom' => [
                'required'   => 'Le nom du sport est obligatoire.',
                'is_unique'  => 'Ce sport existe déjà dans la base de données.',
                'min_length' => 'Le nom doit comporter au moins 2 caractères.',
                'max_length' => 'Le nom ne peut pas dépasser 150 caractères.',
            ],
            'apport_poids' => [
                'required' => "L'effet sur le poids est obligatoire.",
                'in_list'  => 'Valeur invalide pour l\'effet sur le poids.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->sportModel->insert([
            'nom'          => $this->request->getPost('nom'),
            'apport_poids' => (int) $this->request->getPost('apport_poids'),
        ]);

        return redirect()->to('/admin/sports')->with('success', 'Sport ajouté avec succès.');
    }

    // ─────────────────────────────────────────────
    // SHOW  –  GET /admin/sports/show/{id}
    // ─────────────────────────────────────────────
    public function show(int $id): string
    {
        $sport = $this->sportModel->find($id);

        if (! $sport) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Sport #$id introuvable.");
        }

        return view('admin/sport/show', [
            'title' => 'Détail du sport',
            'sport' => $sport,
        ]);
    }

    // ─────────────────────────────────────────────
    // EDIT FORM  –  GET /admin/sports/edit/{id}
    // ─────────────────────────────────────────────
    public function edit(int $id): string
    {
        $sport = $this->sportModel->find($id);

        if (! $sport) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Sport #$id introuvable.");
        }

        return view('admin/sport/edit', [
            'title'      => 'Modifier le sport',
            'sport'      => $sport,
            'validation' => \Config\Services::validation(),
        ]);
    }

    // ─────────────────────────────────────────────
    // UPDATE  –  POST /admin/sports/update/{id}
    // ─────────────────────────────────────────────
    public function update(int $id)
    {
        $sport = $this->sportModel->find($id);

        if (! $sport) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Sport #$id introuvable.");
        }

        $rules = [
            'nom'          => "required|min_length[2]|max_length[150]|is_unique[sport.nom,id,{$id}]",
            'apport_poids' => 'required|in_list[-1,0,1]',
        ];

        $messages = [
            'nom' => [
                'required'   => 'Le nom du sport est obligatoire.',
                'is_unique'  => 'Ce nom est déjà utilisé par un autre sport.',
                'min_length' => 'Le nom doit comporter au moins 2 caractères.',
                'max_length' => 'Le nom ne peut pas dépasser 150 caractères.',
            ],
            'apport_poids' => [
                'required' => "L'effet sur le poids est obligatoire.",
                'in_list'  => 'Valeur invalide pour l\'effet sur le poids.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->sportModel->update($id, [
            'nom'          => $this->request->getPost('nom'),
            'apport_poids' => (int) $this->request->getPost('apport_poids'),
        ]);

        return redirect()->to('/admin/sports')->with('success', 'Sport mis à jour avec succès.');
    }

    // ─────────────────────────────────────────────
    // DELETE  –  GET /admin/sports/delete/{id}
    // ─────────────────────────────────────────────
    public function delete(int $id)
    {
        $sport = $this->sportModel->find($id);

        if (! $sport) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Sport #$id introuvable.");
        }

        $this->sportModel->delete($id);

        return redirect()->to('/admin/sports')->with('success', 'Sport supprimé avec succès.');
    }
}