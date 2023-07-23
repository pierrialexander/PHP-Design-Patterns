<?php

require_once 'classes/active_record/Produto.php';
require_once 'classes/api/Connection.php';

try {
    $conn = Connection::open('estoque');
    Produto::setConnection($conn);
    
    $produto = new Produto();
    $produto->descricao = "Café Novo";
    $produto->
    
    
} catch (Exception $e) {
    echo $e->getMessage();
}
