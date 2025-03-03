<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Clientes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'not null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true,
                'null' => false,
            ],
            'senha' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => false,
            ],
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => '11',
                'unique' => true,
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
            'perfil' => [
                'type' => 'ENUM',
                'constraint' => ['admin', 'user'],
                'default' => 'user',
            ],
        ]);
    
        $this->forge->addKey('id', true);

        $this->forge->createTable('clientes');
    }

    public function down()
    {
        $this->forge->dropTable('clientes');
    }
}
