<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

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


        // Utilisateur authentifié : laisser la requête se poursuivre. Le
        // contrôleur d'accueil (route "/") gère la redirection selon le rôle.
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la requête.
    }
}