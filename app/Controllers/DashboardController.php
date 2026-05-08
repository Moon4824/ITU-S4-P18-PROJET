<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'user'  => [
                'id'      => session()->get('user_id'),
                'role_id' => session()->get('user_role_id'),
                'role'    => session()->get('user_role'),
                'name'    => session()->get('user_name'),
                'email'   => session()->get('user_email'),
            ],
        ]);
    }
}
