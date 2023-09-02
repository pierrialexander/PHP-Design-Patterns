<?php
require_once 'classes/table_data_gateway/ProdutoGateway.php';

try {
    $conn = new PDO('sqlite:database/estoque.db');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    ProdutoGateway::setConnection($conn);

    //======================================TESTE PARA CRIAR E INSERIR
    /*
    $data = new stdClass();
    $data->descricao = 'Vinho';
    $data->estoque = 15;
    $data->preco_custo = 12;
    $data->preco_venda = 33;
    $data->codigo_barras = '1236548799';
    $data->data_cadastro = date('Y-m-d');
    $data->origem = 'N';

    $gateway = new ProdutoGateway();
    $gateway->save($data);
    */

    //=======================================TESTE DE CONSULTA E UPDATE
    /*
    $gateway = new ProdutoGateway();
    $produto = $gateway->find(1);
    $produto->estoque += 5;
    $gateway->save($produto);
    */

    //=======================================TESTE DE CONSULTA ALL E IMPRESSÃO
    /*
    $gateway = new ProdutoGateway();
    foreach ($gateway->all() as $produto)
    {
        print $produto->descricao . '<br>';
    }
    */



}
catch (PDOException $e)
{
    print $e->getMessage();
}