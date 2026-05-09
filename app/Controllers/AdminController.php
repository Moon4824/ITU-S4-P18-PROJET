<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function index()
    {

        return view('/admin/dashbord', [
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
