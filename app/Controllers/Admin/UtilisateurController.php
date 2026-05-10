<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UtilisateurModel;
use App\Models\UserRoleModel;

class UtilisateurController extends BaseController
{
    protected UtilisateurModel $utilisateurModel;
    protected UserRoleModel    $roleModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->roleModel        = new UserRoleModel();
    }

    // ─────────────────────────────────────────────
    // LIST  –  GET /admin/utilisateurs
    // ─────────────────────────────────────────────
    public function index(): string
    {
        $keyword = $this->request->getGet('q');
        // On récupère le rôle pour le filtre
        $roleFilter = $this->request->getGet('role');

        $builder = $this->utilisateurModel
            ->select('utilisateur.*, user_role.role AS role_label')
            ->join('user_role', 'user_role.id = utilisateur.id_role', 'left')
            ->orderBy('utilisateur.nom', 'ASC');

        if ($keyword) {
            $builder->groupStart()
                ->like('utilisateur.nom', $keyword)
                ->orLike('utilisateur.email', $keyword)
                ->groupEnd();
        }

        if ($roleFilter) {
            $builder->where('user_role.role', $roleFilter);
        }

        return view('admin/utilisateur/index', [
            'title'    => 'Gestion des utilisateurs',
            'subtitle' => 'Liste de tous les comptes enregistrés',
            'users'    => $builder->paginate(15),
            'pager'    => $this->utilisateurModel->pager,
            'keyword'  => $keyword,
            'roleFilter' => $roleFilter,
            'roles'    => $this->roleModel->findAll(),
        ]);
    }

    // ─────────────────────────────────────────────
    // CREATE FORM  –  GET /admin/utilisateurs/create
    // ─────────────────────────────────────────────
    public function create(): string
    {
        return view('admin/utilisateur/create', [
            'title'    => 'Ajouter un administrateur',
            'subtitle' => 'Création d\'un compte admin',
        ]);
    }

    // ─────────────────────────────────────────────
    // STORE  –  POST /admin/utilisateurs/store
    // ─────────────────────────────────────────────
    public function store()
    {
        // Règles validées uniquement pour les champs demandés
        $rules = [
            'nom'            => 'required|min_length[2]|max_length[100]',
            'email'          => 'required|valid_email|max_length[150]|is_unique[utilisateur.email]',
            'mot_de_passe'   => 'required|min_length[8]',
        ];

        $messages = [
            'nom'            => ['required' => 'Le nom est obligatoire.'],
            'email'          => [
                'required'    => 'L\'email est obligatoire.',
                'valid_email' => 'L\'email n\'est pas valide.',
                'is_unique'   => 'Cet email est déjà utilisé.',
            ],
            'mot_de_passe'   => [
                'required'   => 'Le mot de passe est obligatoire.',
                'min_length' => 'Le mot de passe doit contenir au moins 8 caractères.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Récupération de l'ID du rôle 'admin'
        $adminRole = $this->roleModel->findByRole('admin');
        $adminRoleId = $adminRole ? $adminRole['id'] : 1; // Fallback sur 1 si non trouvé

        $this->utilisateurModel->insert([
            'nom'            => $this->request->getPost('nom'),
            'email'          => $this->request->getPost('email'),
            'mot_de_passe'   => password_hash($this->request->getPost('mot_de_passe'), PASSWORD_BCRYPT, ['cost' => 10]),
            'id_role'        => $adminRoleId, // Forcé à Admin
            // Champs requis par la BDD mais hors formulaire - Valeurs par défaut
            'date_naissance' => '1990-01-01', 
            'genre'          => 'homme',
            'poids_actuel'   => 70.0,
            'taille'         => 1.75,
            'est_gold'       => 0,
            'solde_monnaie'  => 0,
        ]);

        return redirect()->to('/admin/utilisateurs')->with('success', 'Administrateur créé avec succès.');
    }

    // ─────────────────────────────────────────────
    // SHOW  –  GET /admin/utilisateurs/show/{id}
    // ─────────────────────────────────────────────
    public function show(int $id): string
    {
        $user = $this->utilisateurModel->findByIdWithRole($id);

        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Utilisateur #$id introuvable.");
        }

        return view('admin/utilisateur/show', [
            'title'    => 'Détail utilisateur',
            'subtitle' => esc($user['nom']),
            'user'     => $user,
        ]);
    }

    // ─────────────────────────────────────────────
    // EDIT FORM  –  GET /admin/utilisateurs/edit/{id}
    // ─────────────────────────────────────────────
    public function edit(int $id): string
    {
        $user = $this->utilisateurModel->findByIdWithRole($id);

        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Utilisateur #$id introuvable.");
        }

        return view('admin/utilisateur/edit', [
            'title'    => 'Modifier l\'utilisateur',
            'subtitle' => esc($user['nom']),
            'user'     => $user,
        ]);
    }

    // ─────────────────────────────────────────────
    // UPDATE  –  POST /admin/utilisateurs/update/{id}
    // ─────────────────────────────────────────────
    public function update(int $id)
    {
        $user = $this->utilisateurModel->find($id);

        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Utilisateur #$id introuvable.");
        }

        // Règles simplifiées pour l'édition admin
        $rules = [
            'nom'            => 'required|min_length[2]|max_length[100]',
            'email'          => "required|valid_email|max_length[150]|is_unique[utilisateur.email,id,{$id}]",
            'mot_de_passe'   => 'permit_empty|min_length[8]', // Permettre vide = pas de changement
        ];

        $messages = [
            'nom'          => ['required' => 'Le nom est obligatoire.'],
            'email'        => [
                'required'    => 'L\'email est obligatoire.',
                'valid_email' => 'L\'email n\'est pas valide.',
                'is_unique'   => 'Cet email est déjà utilisé par un autre compte.',
            ],
            'mot_de_passe' => ['min_length' => 'Le mot de passe doit contenir au moins 8 caractères.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom'            => $this->request->getPost('nom'),
            'email'          => $this->request->getPost('email'),
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        $newPassword = $this->request->getPost('mot_de_passe');
        if (! empty($newPassword)) {
            $data['mot_de_passe'] = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
        }

        $this->utilisateurModel->update($id, $data);

        return redirect()->to('/admin/utilisateurs')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    // ─────────────────────────────────────────────
    // DELETE  –  GET /admin/utilisateurs/delete/{id}
    // ─────────────────────────────────────────────
    public function delete(int $id)
    {
        $user = $this->utilisateurModel->find($id);

        if (! $user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Utilisateur #$id introuvable.");
        }

        // Empêcher la suppression de son propre compte
        if ((int) session()->get('user_id') === $id) {
            return redirect()->to('/admin/utilisateurs')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $this->utilisateurModel->delete($id);

        return redirect()->to('/admin/utilisateurs')->with('success', 'Utilisateur supprimé avec succès.');
    }
}