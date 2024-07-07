<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mensagens de confirmação do sistema
    |--------------------------------------------------------------------------
    |
    | As mensagens definidas para cada operação neste arquivo irão afetar todos
    | os CRUDs do sistema.
    */
    'store:success' => 'O cadastro de :Entity foi realizado com sucesso.',
    'store:error' => 'Não foi possível realizar o cadastro de :Entity',

    'update:success' => 'A atualização de :Entity foi realizada com sucesso.',
    'update:error' => 'Não foi possível realizar a atualização de :Entity',

    'destroy:confirmation' => 'Excluir :Entity?',
    'destroy:success' => 'A exclusão de :Entity foi realizada com sucesso.',
    'destroy:error' => 'Não foi possível excluir :Entity',
    'destroy:error.reference' => 'Não foi possível excluir :Entity. O registro possui referência no sistema',
];  
