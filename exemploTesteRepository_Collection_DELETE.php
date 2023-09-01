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
    Transaction::setLogger(new LoggerTXT(__DIR__ . '/database/log_Collection_delete.txt'));

    $criteria = new Criteria();
    $criteria->add('descricao', 'like', '%Bac%');
    $criteria->add('descricao', 'like', '%Salm%', 'or');

    $repository = new Repository('Produto');
    $repository->delete($criteria);

    Transaction::close();
}
catch (Exception $e) {
    Transaction::rollback();
    print $e->getMessage();
}