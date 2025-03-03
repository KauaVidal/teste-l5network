<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ResponseFormat;
use App\Models\ClientesModel;
use Firebase\JWT\JWT;

class LoginController extends BaseController
{
    public function index()
    {
        $clienteModel = new ClientesModel();
        $request = $this->request->getJSON();
        $data = $request->parametros;

        $email = $data->email;
        $senha = $data->senha;

        $cliente = $clienteModel->where('email', $data->email)->first();

        if($cliente == null){
            return $this->response->setStatusCode(401)
            ->setJSON(ResponseFormat::formatResponse(401, 'Erro ao logar', "Email ou senhas Invalidos"));
        }

        $verificar_senha = password_verify($senha, $cliente['senha']);

        if($verificar_senha == false){
            return $this->response->setStatusCode(401)
            ->setJSON(ResponseFormat::formatResponse(401, 'Erro ao logar', "Email ou senhas Invalidos"));
        }

        $key = getenv('JWT_SECRET');
        $iat = time();
        $exp = time() + 3600;

        $payload = array(
            "iat" => $iat,
            "exp" => $exp,
            "email" => $cliente['email'],
            "perfil" => $cliente['perfil'],
            "id" => $cliente['id']
        );

        $token = JWT::encode($payload, $key, 'HS256');

        return $this->response->setStatusCode(200)
        ->setJSON(ResponseFormat::formatResponse(200, 'Login efetuado com sucesso', "Token de acesso : " . $token));

    }
}
