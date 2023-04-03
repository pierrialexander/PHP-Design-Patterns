<?php
require_once 'classes/table_data_gateway/ProdutoGateway.php';
require_once 'classes/table_data_gateway/Produto.php';

try {
    $conn = new PDO('sqlite:database/estoque.db');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    Produto::setConnection($conn);

    $produtos = Produto::all();
    foreach ($produtos as $produto)
    {
        $produto->delete();
    }

    $data = new Produto();
    $data->descricao = 'Vinho do Porto';
    $data->estoque = 15;
    $data->preco_custo = 332;
    $data->preco_venda = 600;
    $data->codigo_barras = '17411548799';
    $data->data_cadastro = date('Y-m-d');
    $data->origem = 'N';
    $data->save();

    $outro = Produto::find(1);
    print 'Descrição: ' . $outro->descricao . '<br>';
    print 'Descrição: ' . $outro->getMargemLucro() . '<br>';
    $outro->registraCompra(14, 5);
    $outro->save();


}
catch (PDOException $e)
{
    print $e->getMessage();
}