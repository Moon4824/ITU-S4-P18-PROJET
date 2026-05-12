<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = session();

        if (! $session->get('isLoggedIn') || ! $session->get('user_id')) {
            return redirect()->to('/auth/login');
        }

        $role = (string) $session->get('user_role');
        $id_role = $session->get('id_role') ?? $session->get('user_role_id');

        if ($role === 'admin' || $id_role == 1) {
            return redirect()->to('/admin');
        }

        return redirect()->to('/user');
    }
}
