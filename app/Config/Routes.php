<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ROTAS CRUDS-CLIENTES Backend 
$routes->post('/register', 'ClientesController::criarCliente');
$routes->post('/login', 'LoginController::index');
$routes->get('/clientes', 'ClientesController::findAll', ['filter' => 'authFilter:admin']);
$routes->delete('/clientes/(:num)', 'ClientesController::deleteById/$1', ['filter' => 'authFilter:admin']);
$routes->put('/clientes/(:num)', 'ClientesController::updateClienteById/$1', ['filter' => 'authFilter']);

// ROTAS CRUDS-PRODUTOS Backend
$routes->post('/produtos', 'ProdutosController::criarProduto', ['filter' => 'authFilter:admin']);
$routes->delete('/produtos/(:num)', 'ProdutosController::deleteById/$1', ['filter' => 'authFilter:admin']);
$routes->put('/produtos/(:num)', 'ProdutosController::updateById/$1', ['filter' => 'authFilter:admin']);
$routes->get('/produtos', 'ProdutosController::findAll', ['filter' => 'authFilter']);

// ROTAS CRUDS-PEDIDOS Backend
$routes->post('/pedidos/(:num)', 'PedidoController::criarPedido/$1', ['filter' => 'authFilter:admin']);
$routes->get('/pedidos', 'PedidoController::findAll', ['filter' => 'authFilter']);
$routes->get('/pedidos/(:num)', 'PedidoController::findById/$1', ['filter' => 'authFilter']);
$routes->delete('/pedidos/(:num)', 'PedidoController::deleteById/$1', ['filter' => 'authFilter']);
$routes->put('/pedidos/(:num)', 'PedidoController::updateById/$1', ['filter' => 'authFilter']);
$routes->post('/pedidos/adicionar-produto/(:num)', 'PedidoController::adicionarProduto/$1', ['filter' => 'authFilter:admin']);  