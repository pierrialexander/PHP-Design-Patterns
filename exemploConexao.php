<?php

require_once 'classes/active_record/Produto.php';
require_once 'classes/api/Connection.php';

try {
    $conn = Connection::open('estoque');
    Produto::setConnection($conn);
    
    $produto = new Produto();
    $produto->descricao = "Banana Nova";
    $produto->estoque = 115;
    $produto->preco_custo = 40;
    $produto->preco_venda = 75;
    $produto->codigo_barras = '123188823';
    $produto->data_cadastro = date('Y-m-d');
    $produto->origem = 'N';
    $produto->save();
} 
catch (Exception $e) 
{
    echo $e->getMessage();
}
