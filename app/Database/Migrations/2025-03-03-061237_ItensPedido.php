<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ItensPedido extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pedido_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'produto_id' => [
                'type' => 'INT',
                'null' => false,
            ],
            'quantidade_produto' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'valor_unitario' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id');
        $this->forge->addForeignKey('produto_id', 'produtos', 'id');

        $this->forge->createTable('itens_pedido');
    }

    public function down()
    {
        $this->forge->dropTable('itens_pedido');
    }
}
