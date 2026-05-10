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
    
}
