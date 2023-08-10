<?php

require_once 'classes/active_record/ProdutoTransaction.php';
require_once 'classes/api/Connection.php';
require_once 'classes/api/Transaction.php';

try
{

    Transaction::open('estoque');

    $produto = new ProdutoTransaction();
    $produto->descricao = "Chocolate Amargo";
    $produto->estoque = 15;
    $produto->preco_custo = 10;
    $produto->preco_venda = 35;
    $produto->codigo_barras = '123789823';
    $produto->data_cadastro = date('Y-m-d');
    $produto->origem = 'N';
    $produto->save();

    Transaction::close();
}
catch (Exception $e)
{
    Transaction::rollback();
    echo $e->getMessage();
}
