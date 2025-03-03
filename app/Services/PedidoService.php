<?php

namespace App\Services;

use App\Models\ItensPedidoModel;
use App\Models\PedidosModel;
use App\Models\ProdutosModel;

class pedidoService
{
    private $pedidosModel;
    private $itensPedidoModel;
    private $produtosModel;

    public function __construct()
    {
        $this->pedidosModel = new PedidosModel();
        $this->itensPedidoModel = new ItensPedidoModel();
        $this->produtosModel = new ProdutosModel();
    }

    public function criarPedido(int $cliente_id)
    {
        if ($cliente_id === null) {
            throw new \Exception('Cliente é obrigatório', 400);
        }

        $ultimoPedido = $this->findLastByClienteId($cliente_id);

        if ($ultimoPedido['status'] === 'Em Aberto') {
            throw new \Exception('Cliente já possui um pedido em aberto', 400);
        }
        

        $pedido = [
            "cliente_id" => $cliente_id,
            'status' => 'Em aberto',
        ];


       try {
            $this->pedidosModel->save($pedido);
            $pedidoId = $this->pedidosModel->insertID();
            $criado = $this->pedidosModel->find($pedidoId);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao cadastrar', 500);
        }


        return $criado;
    }

    public function deleteById(int $id)
    {
        $pedido = $this->pedidosModel->find($id);

        if (!$pedido) {
            throw new \Exception('Pedido não encontrado', 404);
        }

        $this->pedidosModel->delete($id);

        return $pedido;
    }

    public function updateById(int $id, object $pedido)
    {
        $pedidoEncontrado = $this->pedidosModel->find($id);
        $pedidoAtualizar = [];

        if (!$pedidoEncontrado) {
            throw new \Exception('Pedido não encontrado', 404);
        }

        if (isset($pedido->status)) {
            $pedidoAtualizar['status'] = $pedido->status;
        }

        if (isset($pedido->cliente_id)) {
            $pedidoAtualizar['cliente_id'] = $pedido->cliente_id;
        }

        $this->pedidosModel->update($id, $pedidoAtualizar);

        return $this->pedidosModel->find($id);
    }

    public function findAll()
    {
        return $this->pedidosModel->findAll();
    }

    public function findById(int $id)
    {
        $pedido = $this->pedidosModel->find($id);

        if (!$pedido) {
            throw new \Exception('Pedido não encontrado', 404);
        }

        $itendPedido = $this->itensPedidoModel->where('pedido_id', $pedido['id'])->findAll();

        $pedido['valor_total'] = $this->calcularValorTotal($itendPedido);
        $pedido['produtos'] = $itendPedido;


        return $pedido;
    }

    private function calcularValorTotal(array $itensPedido)
    {
        $valorTotal = 0.00;

        foreach ($itensPedido as $item) {
            $valorTotal += $item['quantidade_produto'] * $item['valor_unitario'];
        }

        return $valorTotal;
    }

    public function findLastByClienteId(int $cliente_id)
    {
        $pedido = $this->pedidosModel->where('cliente_id', $cliente_id)
        ->orderBy('id', 'DESC')
        ->first();

        if (!$pedido) {
            throw new \Exception('Pedido não encontrado', 404);
        }

        return $pedido;
    }

    public function adicionarProduto(object $data, int $cliente_id)
    {

        $pedido = $this->findLastByClienteId($cliente_id);


        if ($pedido['status'] !== 'Em Aberto') {
            throw new \Exception('Pedido já foi finalizado ou cancelado', 400);
        }

        $produto = $this->produtosModel->find($data->produto_id);

        if (!$produto) {
            throw new \Exception('Produto não encontrado', 404);
        }

        $itemPedido = [
            'pedido_id' => $pedido['id'],
            'produto_id' => $produto['id'],
            'quantidade_produto' => $data->quantidade,
            'valor_unitario' => $produto['preco'],
        ];

        $this->itensPedidoModel->save($itemPedido);

        return $itemPedido;
    }

}