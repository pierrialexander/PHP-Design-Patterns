<?php
require_once 'classes/data_mapper/Produto.php';
require_once 'classes/data_mapper/Venda.php';
require_once 'classes/data_mapper/VendaMapper.php';

try {
    $p1 = new Produto;
    $p1->id = 1;
    $p1->preco = 120;
    
    $p2 = new Produto;
    $p2->id = 2;
    $p2->preco = 14;
    
    $venda = new Venda;
    $venda->addItem(4, $p1);
    $venda->addItem(20, $p2);
    
    $conn = new PDO('sqlite:database/estoque.db');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    VendaMapper::setConnection($conn);
    
    VendaMapper::save($venda);
        
} 
catch (Exception $ex) {
    $ex->getMessage();
}