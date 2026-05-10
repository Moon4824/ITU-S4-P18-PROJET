<?php

namespace App\Controllers;

use App\Models\ObjectifModel;
use App\Models\UtilisateurModel;

class UserController extends BaseController
{
    protected ObjectifModel $objectifModel;
    protected UtilisateurModel $utilisateurModel;

    public function __construct()
    {
        $this->objectifModel = new ObjectifModel();
        $this->utilisateurModel = new UtilisateurModel();
    }

    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $user = $this->utilisateurModel->findByIdWithRole($userId);

        if ($user === null) {
            return redirect()->to('/auth/login');
        }

        $objective = $this->objectifModel->findLatestByUser($userId);

        if ($objective === null) {
            return redirect()->to('/objectifs/choose');
        }

        session()->set([
            'selected_objective_id' => (int) ($objective['id_type_objectif'] ?? 0),
            'selected_objective_label' => (string) ($objective['type_objectif_label'] ?? ''),
        ]);

        return view('user/dashboard', [
            'title' => 'Dashboard',
            'user'  => [
                'id'      => $user['id'] ?? session()->get('user_id'),
                'role_id' => $user['id_role'] ?? session()->get('user_role_id'),
                'role'    => $user['role_label'] ?? session()->get('user_role'),
                'name'    => $user['nom'] ?? session()->get('user_name'),
                'email'   => $user['email'] ?? session()->get('user_email'),
            ],
        ]);
    }

    public function profile()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $user = $this->utilisateurModel->findByIdWithRole($userId);

        if ($user === null) {
            return redirect()->to('/auth/login');
        }

        // Générer les initiales
        $name = (string) ($user['nom'] ?? 'U');
        $initials = substr(strtoupper($name), 0, 2);

        // Préparer les données du profil
        $profile = [
            'nom'           => $user['nom'] ?? '',
            'email'         => $user['email'] ?? '',
            'date_naissance' => $user['date_naissance'] ?? '',
            'genre'         => $user['genre'] ?? '',
            'poids_actuel'  => $user['poids_actuel'] ?? '',
            'taille'        => $user['taille'] ?? '',
            'solde_monnaie' => $user['solde_monnaie'] ?? 0,
            'est_gold'      => $user['est_gold'] ?? 0,
        ];

        return view('user/profile', [
            'title'    => 'Profil utilisateur',
            'user'     => [
                'id'      => $user['id'] ?? session()->get('user_id'),
                'role_id' => $user['id_role'] ?? session()->get('user_role_id'),
                'role'    => $user['role_label'] ?? session()->get('user_role'),
                'name'    => $user['nom'] ?? session()->get('user_name'),
                'email'   => $user['email'] ?? session()->get('user_email'),
            ],
            'profile'  => $profile,
            'initials' => $initials,
        ]);
    }

    public function editProfile()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $user = $this->utilisateurModel->findByIdWithRole($userId);

        if ($user === null) {
            return redirect()->to('/auth/login');
        }

        $name = (string) ($user['nom'] ?? 'U');
        $initials = substr(strtoupper($name), 0, 2);

        return view('user/profile_edit', [
            'title'    => 'Modifier mon profil',
            'user'     => [
                'id'      => $user['id'] ?? session()->get('user_id'),
                'role_id' => $user['id_role'] ?? session()->get('user_role_id'),
                'role'    => $user['role_label'] ?? session()->get('user_role'),
                'name'    => $user['nom'] ?? session()->get('user_name'),
                'email'   => $user['email'] ?? session()->get('user_email'),
            ],
            'profile'  => [
                'nom'            => $user['nom'] ?? '',
                'email'          => $user['email'] ?? '',
                'date_naissance' => $user['date_naissance'] ?? '',
                'genre'          => $user['genre'] ?? '',
                'poids_actuel'   => $user['poids_actuel'] ?? '',
                'taille'         => $user['taille'] ?? '',
                'solde_monnaie'  => $user['solde_monnaie'] ?? 0,
            ],
            'initials' => $initials,
        ]);
    }

    public function updateProfile()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userId = (int) (session()->get('user_id') ?? 0);

        if ($userId <= 0) {
            return redirect()->to('/auth/login');
        }

        $user = $this->utilisateurModel->find($userId);

        if ($user === null) {
            return redirect()->to('/auth/login');
        }

        $rules = [
            'nom'            => 'required|min_length[2]|max_length[100]',
            'email'          => "required|valid_email|max_length[150]|is_unique[utilisateur.email,id,{$userId}]",
            'date_naissance' => 'required|valid_date[Y-m-d]',
            'genre'          => 'required|in_list[homme,femme]',
            'poids_actuel'   => 'required|decimal|greater_than[0]',
            'taille'         => 'required|decimal|greater_than[0]|less_than[3]',
        ];

        $messages = [
            'nom' => [
                'required' => 'Le nom est obligatoire.',
                'min_length' => 'Le nom doit contenir au moins 2 caractères.',
            ],
            'email' => [
                'required'    => 'L\'email est obligatoire.',
                'valid_email' => 'L\'email n\'est pas valide.',
                'is_unique'   => 'Cet email est déjà utilisé par un autre compte.',
            ],
            'date_naissance' => ['required' => 'La date de naissance est obligatoire.'],
            'genre' => ['required' => 'Le genre est obligatoire.'],
            'poids_actuel' => ['required' => 'Le poids est obligatoire.'],
            'taille' => ['required' => 'La taille est obligatoire.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom'            => trim((string) $this->request->getPost('nom')),
            'email'          => trim((string) $this->request->getPost('email')),
            'date_naissance' => $this->request->getPost('date_naissance'),
            'genre'          => $this->request->getPost('genre'),
            'poids_actuel'   => (float) $this->request->getPost('poids_actuel'),
            'taille'         => (float) $this->request->getPost('taille'),
        ];

        $this->utilisateurModel->update($userId, $data);

        session()->set([
            'user_name'  => $data['nom'],
            'user_email' => $data['email'],
        ]);

        return redirect()->to('/user/profile')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
    
}
