<?php

namespace App\Controllers;

use App\Models\TypeObjectifModel;

class ObjectifController extends BaseController
{
    protected TypeObjectifModel $typeObjectifModel;

    public function __construct()
    {
        $this->typeObjectifModel = new TypeObjectifModel();
    }

    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        return view('user/objectifs/index', [
            'title' => 'Choisir mes objectifs',
            'user' => [
                'id' => session()->get('user_id'),
                'role' => session()->get('user_role'),
                'name' => session()->get('user_name'),
                'email' => session()->get('user_email'),
            ],
            'objectifs' => $this->typeObjectifModel->findAllOrdered(),
            'selectedObjective' => session()->get('selected_objective_label'),
        ]);
    }

    public function choose()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->to('/objectifs');
        }

        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $objectiveId = (int) $this->request->getPost('id_type_objectif');
        $objective = $this->typeObjectifModel->find($objectiveId);

        if ($objective === null) {
            return redirect()->to('/objectifs');
        }

        session()->set([
            'selected_objective_id' => (int) $objective['id'],
            'selected_objective_label' => (string) $objective['libelle'],
        ]);

        return redirect()->to('/');
    }
    
}
