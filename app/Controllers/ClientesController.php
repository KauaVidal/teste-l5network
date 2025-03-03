<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Dto\ClienteDTO;
use App\Exceptions\ValidationException;
use App\Libraries\ResponseFormat;
use App\Models\ClientesModel;
use App\Services\ClienteService;

class ClientesController extends BaseController
{
    protected $request;
    protected $clienteModel;
    protected $clientesDTO;
    protected $clienteService;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->clienteModel = new ClientesModel();
        $this->clientesDTO = new ClienteDTO();
        $this->clienteService = new ClienteService();
    }

    // ENDPOINT PARA CRIAR CLIENTE  
    public function criarCliente()
    {
        $request = $this->request->getJSON();
        $data = $request->parametros;

        try {
            $responseDto = $this->clienteService->criarCliente($data);
        } catch (ValidationException $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao cadastrar', $e->getErrors()));
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao cadastrar', $e->getMessage()));
        }

        return $this->response->setStatusCode(201)
            ->setJSON(ResponseFormat::formatResponse(201, 'Cliente cadastrado com sucesso', $responseDto));
    }

    // ENTPOIINT PARA BUSCAR TODOS OS CLIENTES
    public function findAll()
    {
        $clientes = $this->clienteModel->findAll();

        if (empty($clientes)) {
            return $this->response->setStatusCode(204)->setJSON('');
        }

        $clientesDTO = array_map(function ($cliente) {
            $dto = $this->clientesDTO->modelToDto($cliente);
            return $dto->toArray();
        }, $clientes);

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Clientes encontrados', $clientesDTO));
    }

    // ENDPOINT DELETE
    public function deleteById(int $id)
    {
        try {
            $respondeDto = $this->clienteService->deleteById($id);
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao deletar', $e->getMessage()));
        }

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Cliente deletado com sucesso', $respondeDto));
    }

    // ENDPOINT PARA ATUALIZAR CLIENTE
    public function updateClienteById(int $id)
    {
        $request = $this->request->getJSON();
        $data = $request->parametros;

        try {
            $responseDto = $this->clienteService->updateCliente($id, $data);
        } catch (ValidationException $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao atualizar', $e->getErrors()));
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao atualizar', $e->getMessage()));
        }

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Cliente atualizado com sucesso', $responseDto));
    }
}
