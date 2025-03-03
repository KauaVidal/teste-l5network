<?php

namespace App\Services;

use App\Models\ProdutosModel;

class ProdutoService
{
    private $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutosModel();
    }

    public function criarProduto(object $produto)
    {
        if (empty($produto->nome)) {
            throw new \Exception('Nome do produto é obrigatório', 400);
        }

        if (empty($produto->preco)) {
            throw new \Exception('Preço do produto é obrigatório', 400);
        }

        if ($produto->preco < 0) {
            throw new \Exception('Preço do produto não pode ser negativo', 400);
        }

        $produto = [
            "nome" => $produto->nome,
            'preco' => $produto->preco,
        ];

        $this->produtoModel->save($produto);

        return $produto;
    }

    public function deleteById(int $id)
    {
        $produto = $this->produtoModel->find($id);

        if (!$produto) {
            throw new \Exception('Produto não encontrado', 404);
        }

        $this->produtoModel->delete($id);

        return $produto;
    }

    public function updateById(int $id, object $produto)
    {
        $produtoEncontrado = $this->produtoModel->find($id);
        $produtoAtualizar = [];

        if (!$produtoEncontrado) {
            throw new \Exception('Produto não encontrado', 404);
        }

        if (isset($produto->nome)) {
            $produtoAtualizar['nome'] = $produto->nome;
        }

        if (isset($produto->preco)) {
            if ($produto->preco < 0) {
                throw new \Exception('Valor do produto não pode ser negativo', 400);
            }
            $produtoAtualizar['preco'] = $produto->preco;
        }

        if (isset($produto->pedido_id)) {
            $produtoAtualizar['pedido_id'] = $produto->pedido_id;
        }

        if (empty($produtoAtualizar)) {
            throw new \Exception('Nenhum dado para atualizar', 400);
        }

        $this->produtoModel->update($id, $produtoAtualizar);

        $produtoAtualizado = $this->produtoModel->find($id);

        return $produtoAtualizado;
    }

    public function findAll()
    {
        $produtos = $this->produtoModel->findAll();

        if (empty($produtos)) {
            return [];
        }

        return $produtos;
    }
}
