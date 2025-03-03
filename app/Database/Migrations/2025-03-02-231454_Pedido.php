<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pedidos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Em Aberto', 'Pago', 'Cancelado'],
                'default' => 'Em Aberto',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'cliente_id' => [
                'type' => 'INT',
                'null' => false,
            ],
        ]);
    
        $this->forge->addKey('id');
        $this->forge->addForeignKey('cliente_id', 'clientes', 'id');

        $this->forge->createTable('pedidos');
    }

    public function down()
    {
        $this->forge->dropTable('pedidos');
    }
}
