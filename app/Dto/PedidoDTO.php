<?php

namespace App\Dto;

class PedidoDTO
{
    private int $id;
    private int $id_cliente;
    private string $data_pedido;
    private string $status;
    private float $valor_total;
    private array $produtos;

    public function __construct(int $id = 0, int $id_cliente = 0, string $data_pedido = '', string $status = '', float $valor_total = 0, array $produtos = [])
    {
        $this->id = $id;
        $this->id_cliente = $id_cliente;
        $this->data_pedido = $data_pedido;
        $this->status = $status;
        $this->valor_total = $valor_total;
        $this->produtos = $produtos;
    }

    public function modelToDto(array $pedido, array $produtos): PedidoDTO
    {
        return new PedidoDTO(
            $pedido['id'],
            $pedido['id_cliente'],
            $pedido['data_pedido'],
            $pedido['status'],
            $pedido['valor_total'],
            $produtos
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'id_cliente' => $this->id_cliente,
            'data_pedido' => $this->data_pedido,
            'status' => $this->status,
            'valor_total' => $this->valor_total
        ];
    }
}