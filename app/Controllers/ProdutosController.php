<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ResponseFormat;
use App\Models\ProdutosModel;
use App\Services\ProdutoService;

class ProdutosController extends BaseController
{
    protected $request;
    protected $produtoModel;
    protected $produtoService;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->produtoModel = new ProdutosModel();
        $this->produtoService = new ProdutoService();
    }

    public function criarProduto()
    {
        $request = $this->request->getJSON();
        $data = $request->parametros;

        try {
            $response = $this->produtoService->criarProduto($data);
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao cadastrar', $e->getMessage()));
        }

        return $this->response->setStatusCode(201)
            ->setJSON($response);
    }

    public function deleteById(int $id)
    {
        try {
            $response = $this->produtoService->deleteById($id);
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao deletar', $e->getMessage()));
        }

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Produto deletado com sucesso', $response));
    }

    public function updateById(int $id)
    {
        $request = $this->request->getJSON();
        $data = $request->parametros;

        try {
            $response = $this->produtoService->updateById($id, $data);
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao atualizar', $e->getMessage()));
        }

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Produto atualizado com sucesso', $response));
    }

    public function findAll()
    {
        $produtos = $this->produtoModel->findAll();

        if (empty($produtos)) {
            return $this->response->setStatusCode(204)->setJSON('');
        }

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Produtos encontrados', $produtos));
    }
}
