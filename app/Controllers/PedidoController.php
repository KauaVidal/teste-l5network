<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\ResponseFormat;
use App\Models\PedidosModel;
use App\Services\pedidoService;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * @OA\Info(title="Pedido API", version="1.0.0")
 */
class PedidoController extends BaseController
{
    private $pedidosModel;
    private $pedidoService;

    public function __construct()
    {
        $this->pedidosModel = new PedidosModel();
        $this->pedidoService = new pedidoService();
    }

    /**
     * @OA\Post(
     *     path="/pedidos",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"cliente_id"},
     *             @OA\Property(property="cliente_id", type="integer", example="1")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Create a new pedido"),
     *     @OA\Response(response="400", description="Bad Request")
     * )
     */
    public function criarPedido(int $id)
    {
        try {
            $pedido = $this->pedidoService->criarPedido($id);
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao cadastrar', $e->getMessage()));
        }

        return $this->response->setStatusCode(201)
            ->setJSON(ResponseFormat::formatResponse(201, 'Pedido cadastrado com sucesso', $pedido));
    }

    public function deleteById(int $id)
    {
        try {
            $pedido = $this->pedidoService->deleteById($id);
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao deletar', $e->getMessage()));
        }

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Pedido deletado com sucesso', $pedido));
    }

    public function updateById(int $id)
    {
        $request = $this->request->getJSON();
        $pedido = $request->parametros;

        try {
            $pedido = $this->pedidoService->updateById($id, $pedido);
        } catch (\Exception $e) {
            return $this->response->setStatusCode($e->getCode())
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao atualizar', $e->getMessage()));
        }

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Pedido atualizado com sucesso', $pedido));
    }

    public function findAll()
    {
        $pedidos = $this->pedidosModel->findAll();

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Pedidos encontrados', $pedidos));
    }

    public function findById(int $id)
    {

        try {
            $pedido = $this->pedidoService->findById($id);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(404)
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao encontrar pedido', $e->getMessage()));
        }

        return $this->response->setStatusCode(200)
            ->setJSON(ResponseFormat::formatResponse(200, 'Pedido encontrado', $pedido));
    }

    public function adicionarProduto(int $cliente_id)
    {
        $request = $this->request->getJSON();
        $data = $request->parametros;

        try {
            $pedido = $this->pedidoService->adicionarProduto($data, $cliente_id);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)
                ->setJSON(ResponseFormat::formatResponse($e->getCode(), 'Erro ao adicionar produto', $e->getMessage()));
        }

        return $this->response->setStatusCode(201)
            ->setJSON(ResponseFormat::formatResponse(201, 'Produto adicionado com sucesso', $pedido));
    }
}
