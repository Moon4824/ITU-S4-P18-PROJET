<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;

class RegisterController extends BaseController
{
    public function inscription1()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        return view('register/inscription1', [
            'title' => 'Inscription - Étape 1/2',
            'error' => null,
            'nom' => session()->get('register_nom') ?? '',
            'email' => session()->get('register_email') ?? '',
            'date_naissance' => session()->get('register_date_naissance') ?? '',
            'genre' => session()->get('register_genre') ?? '',
        ]);
    }

    public function saveInscription1()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/register/inscription1');
        }

        $nom = trim((string) $this->request->getPost('nom'));
        $email = trim((string) $this->request->getPost('email'));
        $dateNaissance = (string) $this->request->getPost('date_naissance');
        $genre = (string) $this->request->getPost('genre');
        $password = (string) $this->request->getPost('mot_de_passe');

        $rules = [
            'nom' => [
                'label' => 'Nom',
                'rules' => 'required|min_length[3]|max_length[100]',
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[utilisateur.email]',
            ],
            'date_naissance' => [
                'label' => 'Date de naissance',
                'rules' => 'required|valid_date[Y-m-d]',
            ],
            'genre' => [
                'label' => 'Genre',
                'rules' => 'required|in_list[homme,femme]',
            ],
            'mot_de_passe' => [
                'label' => 'Mot de passe',
                'rules' => 'required|min_length[8]',
            ],
        ];

        if (! $this->validate($rules)) {
            return view('register/inscription1', [
                'title' => 'Inscription - Étape 1/2',
                'error' => 'Veuillez corriger les champs indiqués.',
                'validation' => $this->validator,
                'nom' => $nom,
                'email' => $email,
                'date_naissance' => $dateNaissance,
                'genre' => $genre,
            ]);
        }

        session()->set([
            'register_nom' => $nom,
            'register_email' => $email,
            'register_date_naissance' => $dateNaissance,
            'register_genre' => $genre,
            'register_password' => $password,
        ]);

        return redirect()->to('/register/inscription2');
    }

    public function inscription2()
    {
        if (! session()->get('register_email')) {
            return redirect()->to('/register/inscription1');
        }

        return view('register/inscription2', [
            'title' => 'Inscription - Étape 2/2',
            'error' => null,
            'nom' => session()->get('register_nom') ?? '',
            'poids' => '',
            'taille' => '',
        ]);
    }

    public function saveInscription2()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/register/inscription2');
        }

        if (! session()->get('register_email')) {
            return redirect()->to('/register/inscription1');
        }

        $poids = (float) $this->request->getPost('poids');
        $taille = (float) $this->request->getPost('taille');

        $rules = [
            'poids' => [
                'label' => 'Poids',
                'rules' => 'required|greater_than[0]|less_than[500]',
            ],
            'taille' => [
                'label' => 'Taille',
                'rules' => 'required|greater_than[50]|less_than[250]',
            ],
        ];

        if (! $this->validate($rules)) {
            return view('register/inscription2', [
                'title' => 'Inscription - Étape 2/2',
                'error' => 'Veuillez corriger les champs indiqués.',
                'validation' => $this->validator,
                'nom' => session()->get('register_nom') ?? '',
                'poids' => $poids,
                'taille' => $taille,
            ]);
        }

        $utilisateurModel = new UtilisateurModel();
        $userId = $utilisateurModel->createUser([
            'nom' => (string) session()->get('register_nom'),
            'email' => (string) session()->get('register_email'),
            'date_naissance' => (string) session()->get('register_date_naissance'),
            'genre' => (string) session()->get('register_genre'),
            'mot_de_passe' => (string) session()->get('register_password'),
            'poids_actuel' => $poids,
            'taille' => $taille / 100,
            'id_role' => 2,
        ]);

        if (! $userId) {
            return view('register/inscription2', [
                'title' => 'Inscription - Étape 2/2',
                'error' => 'Le compte existe déjà ou l’enregistrement a échoué.',
                'nom' => session()->get('register_nom') ?? '',
                'poids' => $poids,
                'taille' => $taille,
            ]);
        }

        $createdUser = $utilisateurModel->findByIdWithRole((int) $userId);
        if ($createdUser !== null) {
            session()->set($utilisateurModel->buildLoginSessionData([
                'id' => (int) $createdUser['id'],
                'id_role' => (int) $createdUser['id_role'],
                'nom' => (string) $createdUser['nom'],
                'email' => (string) $createdUser['email'],
                'role' => (string) ($createdUser['role_label'] ?? 'utilisateur'),
            ]));
        }

        $imcNom = (string) session()->get('register_nom');
        $imcEmail = (string) session()->get('register_email');
        $imcGenre = (string) session()->get('register_genre');

        session()->set([
            'imc_nom' => $imcNom,
            'imc_email' => $imcEmail,
            'imc_genre' => $imcGenre,
            'imc_poids' => $poids,
            'imc_taille' => $taille,
        ]);

        session()->remove([
            'register_nom',
            'register_email',
            'register_date_naissance',
            'register_genre',
            'register_password',
        ]);

        return redirect()->to('/imc');
    }
}
