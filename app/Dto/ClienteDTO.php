<?php

namespace App\Dto;

class ClienteDTO
{
    private string $nome;
    private string $email;
    private string $cpf;
    private string $perfil;

    public function __construct(string $nome ='', string $email='', string $cpf='', string $perfil='')
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->cpf = $cpf;
        $this->perfil = $perfil;
    }

    public function modelToDto(array $cliente): ClienteDTO
    {
        return new ClienteDTO(
            $cliente['nome'],
            $cliente['email'],
            $cliente['cpf'],
            $cliente['perfil']
        );
    }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'perfil' => $this->perfil
        ];
    }
}