<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Vérifier si l'utilisateur est connecté
        if (!$session->get('isLoggedIn') || !$session->get('user_id')) {
            // Redirection vers la page de connexion générale (route auth/login)
            return redirect()->to('/auth/login')->with('error', 'Veuillez vous connecter pour accéder à l\'administration.');
            
            /* Version JSON (si besoin pour de l'AJAX) :
            return service('response')->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Accès refusé. Authentification requise.'
            ]);
            */
        }

        // 2. Vérifier si l'utilisateur a le rôle 'admin'
        $role = (string) $session->get('user_role');
        
        // Si tu stockes plutôt l'ID du rôle dans la session (1 = admin selon ton UserRoleSeeder)
        $id_role = $session->get('id_role') ?? $session->get('user_role_id');

        // Condition stricte : le rôle doit être 'admin' ou l'ID doit être 1
        if ($role !== 'admin' && $id_role != 1) {
            // S'il est connecté mais n'est pas admin, on le renvoie vers l'accueil/dashboard front-office

            // return redirect()->to('/dashboard')->with('error', 'Accès interdit. Espace réservé aux administrateurs.');
            
            // Version JSON (si besoin pour de l'AJAX) :
            return service('response')->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Accès refusé. Vous n\'avez pas les droits d\'administrateur.'
            ]);
            
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la requête.
    }
}