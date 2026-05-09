<?php

namespace App\Controllers;

class UserController extends BaseController
{
    public function index()
    {

        return view('user/dashboard', [
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
