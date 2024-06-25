<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;


class UserRepository implements UserRepositoryInterface
{

    public function authenticate(Request $request)
    {
        //Simular a autenticação do usuário
        return [
            'id' => 1,
            'name' => 'Caio Dias',
            'email' => 'caio.dias@example.com'
        ];
    }
}
