<?php

namespace App\Filters;

use App\Models\UserRoleModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $allowedRoles = array_values(array_filter($arguments ?? []));

        if ($allowedRoles === []) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Aucun rôle autorisé n’a été défini pour cette route.',
                ]);
        }

        $role = (string) $session->get('user_role');

        if ($role === '' && $session->get('user_role_id')) {
            $roleModel = new UserRoleModel();
            $role = (string) ($roleModel->getRoleLabelById((int) $session->get('user_role_id')) ?? '');
        }

        if ($role === '' || ! in_array($role, $allowedRoles, true)) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Accès refusé. Vous n’avez pas le bon rôle.',
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la requête.
    }
}