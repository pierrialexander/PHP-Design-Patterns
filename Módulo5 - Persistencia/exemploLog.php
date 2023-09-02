<?php

require_once 'classes/active_record/ProdutoTransactionLog.php';
require_once 'classes/api/Connection.php';
require_once 'classes/api/Transaction.php';
require_once 'classes/api/Logger.php';
require_once 'classes/api/LoggerTXT.php';

try
{

    Transaction::open('estoque');
    Transaction::setLogger(new LoggerTXT('C:\Materiais\log.txt'));

    $produto = new ProdutoTransaction();
    $produto->descricao = "Filé Mignon bom";
    $produto->estoque = 153;
    $produto->preco_custo = 140;
    $produto->preco_venda = 365;
    $produto->codigo_barras = '122229823';
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
