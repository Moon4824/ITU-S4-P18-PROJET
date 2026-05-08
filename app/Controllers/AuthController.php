<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function login()
    {
        $email = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('mot_de_passe');
        $utilisateurModel = new UtilisateurModel();

        if (strtolower($this->request->getMethod()) !== 'post') {
            $admin = null;

            try {
                $admin = $utilisateurModel->getAdminLoginData();
            } catch (\Throwable $exception) {
                // Fallback demo: allow the page to render even if the DB is not available yet.
            }

            return view('auth/login', [
                'title' => 'Connexion',
                'error' => null,
                'email' => $admin['email'] ?? 'admin@app.com',
                'password' => 'admin1234',
                'adminName' => $admin['nom'] ?? 'Admin Système',
            ]);
        }

        $rules = [
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email',
            ],
            'mot_de_passe' => [
                'label'  => 'Mot de passe',
                'rules'  => 'required|min_length[4]',
            ],
        ];

        if (! $this->validate($rules)) {
            return view('auth/login', [
                'title' => 'Connexion',
                'error' => 'Données de connexion invalides.',
                'validation' => $this->validator,
                'email' => $email,
                'password' => $password,
            ]);
        }

        try {
            $user = $utilisateurModel->verifyLogin($email, $password);
        } catch (\Throwable $exception) {
            return view('auth/login', [
                'title' => 'Connexion',
                'error' => 'Connexion impossible: la base de données n\'est pas encore disponible.',
                'email' => $email,
                'password' => $password,
                'adminName' => 'Admin Système',
            ]);
        }

        if ($user === null) {
            return view('auth/login', [
                'title' => 'Connexion',
                'error' => 'Email ou mot de passe incorrect.',
                'email' => $email,
                'password' => $password,
            ]);
        }

        $sessionData = $utilisateurModel->buildLoginSessionData($user);
        session()->set($sessionData);

        return redirect()->to('/dashboard');
    }

    public function logout(): ResponseInterface
    {
        session()->destroy();

        return redirect()->to('/auth/login');
    }

    public function me(): ResponseInterface
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Aucune session active.',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'user'   => [
                'id'       => session()->get('user_id'),
                'role_id'  => session()->get('user_role_id'),
                'role'     => session()->get('user_role'),
                'name'     => session()->get('user_name'),
                'email'    => session()->get('user_email'),
            ],
        ]);
    }

}