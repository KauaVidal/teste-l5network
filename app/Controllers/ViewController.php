<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ResponseFormat;
use CodeIgniter\HTTP\ResponseInterface;

class ViewController extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function createPage()
    {
        return view('cadastro');
    }

    public function loginPage()
    {
        return view('login');
    }

    public function entrouPage()
    {
        try {
            return view('entrou');
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao acessar', $e->getMessage()));
        }
    }
}
