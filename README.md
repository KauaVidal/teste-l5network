
# Teste Tecnico l5 Networks

Este projeto foi desenvolvido como um desafio para testar e aprimorar minhas habilidades técnicas. A API RESTful criada oferece funcionalidades robustas, incluindo cadastro e login de clientes, gerenciamento de produtos e processamento de pedidos de compra.


## Pré-Requisitos
- MySQL (https://www.youtube.com/watch?v=a5ul8o76Hqw)
- PHP 8.1.31 (https://www.youtube.com/watch?v=gQ-P0yMBE9U)
- Composer (https://www.youtube.com/watch?v=Dimtx-pQPuA)
## Preparando ambiente

Clone o repositório em sua maquina

```bash
  git clone https://github.com/KauaVidal/teste-l5network
```
Com o Projeto clonado em sua maquina vá até o repositório e instale as dependencias.
```bash
  composer install
```
Vá até o arquivo env e faça uma copia dele com "." na frente para configurar as variaveis de ambiente '.env'. Com isso faça as configurações abaixo para poder rodar o projeto.

```bash
    CI_ENVIRONMENT = development

    database.default.hostname = localhost
    database.default.database = l5network (Coloque o nome do seu banco de dados) 
    database.default.username = root (O nome do seu usuario no banco)
    database.default.password = 0107 (Senha do usuario se nessessario)
    database.default.DBDriver = MySQLi
    database.default.port = 3306 (A porta que o banco está configurado geralmente por padrão é 3306 para MySQL)
```

Gere uma chave de segurança aleatória para autenticação JWT 
```bash
  php -r 'echo base64_encode(random_bytes(32));'
```

O código que aparecer no terminal, insira na variavel de ambiente JWT_SECRET

```bash
  JWT_SECRET = "Xt2qnskr+tmtLRp3pRMlaKkjsBVTLmbF91Yw1HRK4Oc=" 
```
Rode as migrations, para atualizar o seu banco de dados com as tabelas necessarias para a api rodas

```bash
  php spark migrate
```

E com isso você está pronto para rodar o projeto !
```bash
  php spark serve
```
## Funcionalidades

- 
- Preview em tempo real
- Modo tela cheia
- Multiplataforma


## Documentação da API

#### Registrar novo Cliente

```http
  POST /register
```
```body
  Body 
  {
    "parametros":{
        "nome" : "",
        "email" : "",
        "senha" : "",
        "cpf" : "",
        "perfil":""
    }
}
```

#### Fazer login (JWT Duração de 1 hora)

```http
  POST /login
```
```
  Body 
  {
    "parametros":{
        "email" : "",
        "senha" : ""
    }
}
```

#### Registrar Produto


```http
  POST /produtos
```

```
  Body 
  {
    "parametros":{
        "nome": "",
        "preco": 0.00
    }
}
```
##### HEADER

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `Authentication`      | `string` | **Obrigatório**. TOKEN JWT |

### Criar um pedido

```http
  POST /pedidos/$cliente_Id
```

##### HEADER

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `Authentication`      | `string` | **Obrigatório**. TOKEN JWT |




### Adicionar um produto

```http
  POST /pedidos/adicionar-produto/$cliente_Id
```

```
  Body 
  {
    "parametros":{
        "produto_id": ,
        "quantidade": 
    }
}
``` 

##### HEADER

| Parâmetro   | Tipo       | Descrição                                   |
| :---------- | :--------- | :------------------------------------------ |
| `Authentication`      | `string` | **Obrigatório**. TOKEN JWT |
