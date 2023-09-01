<?php
require_once 'classes/api/Transaction.php';
require_once 'classes/api/Connection.php';
require_once 'classes/api/Criteria.php';
require_once 'classes/api/Repository.php';
require_once 'classes/api/Logger.php';
require_once 'classes/api/LoggerTXT.php';
require_once 'classes/api/Record.php';
require_once 'classes/model/Produto.php';

try {
    Transaction::open('estoque');
    Transaction::setLogger(new LoggerTXT(__DIR__ . '/database/log_Collection_get.txt'));

    $criteria = new Criteria();
    $criteria->add('estoque', '>', 20);
    $criteria->add('origem', '=', 'N');

    $repository = new Repository('Produto');
    $produtos = $repository->load($criteria);

    if($produtos)
    {
        foreach ($produtos as $produto)
        {
            print 'ID: ' . $produto->id;
            print ' - Descrição: ' . $produto->descricao;
            print ' - Estoque: '   . $produto->estoque;
            print '<br>';
        }
    }

    print 'Qtde: ' . $repository->count($criteria);

    Transaction::close();
}
catch (Exception $e) {
    Transaction::rollback();
    print $e->getMessage();
}