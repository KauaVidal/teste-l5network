<?php

namespace App\Services;

use App\Dto\ClienteDTO;
use App\Exceptions\ValidationException;
use App\Models\ClientesModel;
use Exception;

class ClienteService
{
    private $clienteModel;
    private $clienteDTO;

    public function __construct()
    {
        $this->clienteModel = new ClientesModel();
        $this->clienteDTO = new ClienteDTO();
    }

    public function criarCliente($cliente)
    {

        $validarCliente = $this->validarCliente($cliente);

        if ($validarCliente !== true) {
            throw new ValidationException($validarCliente);
        }


        $cliente = [
            "nome" => $cliente->nome,
            'email' => $cliente->email,
            'senha' => $cliente->senha,
            'cpf' => $cliente->cpf,
            // 'created_at' => date('Y-m-d H:i:s'),
            'perfil' => $cliente->perfil
        ];


        if ($this->clienteModel->where('email', $cliente['email'])->first()) {
            throw new Exception('Email já cadastrado', 409);
        }

        if ($this->clienteModel->where('cpf', $cliente['cpf'])->first()) {
            throw new Exception('CPF já cadastrado', 409);
        }

        $this->clienteModel->save($cliente);

        $clienteDTO = $this->clienteDTO->modelToDto($cliente);

        return $clienteDTO->toArray();
    }

    public function deleteById(int $id)
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente) {
            throw new Exception('Cliente não encontrado', 404);
        }

        $this->clienteModel->delete($id);

        $clienteDTO = $this->clienteDTO->modelToDto($cliente);

        return $clienteDTO->toArray();
    }

    public function updateCliente(int $id, object $data)
    {
        $cliente = $this->clienteModel->find($id);

        if (!$cliente) {
            throw new Exception('Cliente não encontrado', 404);
        }

        $clienteAtualizado = [];
        $response = [];

        if (isset($data->nome)) {
            $clienteAtualizado['nome'] = $data->nome;
        }

        if (isset($data->email)) {
            $clienteAtualizado['email'] = $data->email;
            if ($this->clienteModel->where('email', $data->email)->first()) {
                throw new Exception('Email já cadastrado', 409);
            }
        }

        if (isset($data->senha)) {
            if (strlen($data->senha) < 6) {
                $response[] = 'Senha deve conter no mínimo 6 caracteres';
            }
            $clienteAtualizado['senha'] = $data->senha;
        }

        if (isset($data->cpf)) {
            if (!is_numeric($data->cpf)) {
                $response[] = 'CPF deve conter apenas números';
            }

            if (strlen($data->cpf) !== 11) {
                $response[] = 'CPF deve conter 11 dígitos';
            }

            if ($this->clienteModel->where('cpf', $data->cpf)->first()) {
                throw new Exception('CPF já cadastrado', 409);
            }
            $clienteAtualizado['cpf'] = $data->cpf;
        }
        
        if (isset($data->perfil)) {
            if ($data->perfil !== 'admin' && $data->perfil !== 'user') {
                $response[] = 'Perfil dever ser : admin ou user';
            }
            $clienteAtualizado['perfil'] = $data->perfil;
        }

        if ($response !== []) {
            throw new ValidationException($response);
        }

        $this->clienteModel->update($id, $clienteAtualizado);

        $clienteDTO = $this->clienteDTO->modelToDto($this->clienteModel->find($id));

        return $clienteDTO->toArray();
    }

    private function validarCliente($data)
    {
        $response = [];

        if (!isset($data->nome) || empty($data->nome)) {
            $response[] = 'Nome é obrigatório';
        }

        if (!isset($data->email) || empty($data->email)) {
            $response[] = 'Email é obrigatório';
        } elseif (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            $response[] = 'Email inválido';
        }

        if (!isset($data->senha) || empty($data->senha)) {
            $response[] = 'Senha é obrigatória';
        } elseif (strlen($data->senha) < 6) {
            $response[] = 'Senha deve conter no mínimo 6 caracteres';
        }

        if (!isset($data->cpf) || empty($data->cpf)) {
            $response[] = 'CPF é obrigatório';
        } elseif (!is_numeric($data->cpf)) {
            $response[] = 'CPF deve conter apenas números';
        } elseif (strlen($data->cpf) !== 11) {
            $response[] = 'CPF deve conter 11 dígitos';
        }

        if (!isset($data->perfil) || empty($data->perfil)) {
            $response[] = 'Perfil é obrigatório';
        } elseif ($data->perfil !== 'admin' && $data->perfil !== 'user') {
            $response[] = 'Perfil dever ser : admin ou user';
        }

        if ($response !== []) {
            return $response;
        }

        return true;
    }
}
