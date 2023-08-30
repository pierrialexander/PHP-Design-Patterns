<?php
require_once 'Requisicao.php';

$dados = [
    "email" => "novoteste@teste.com",
	"password" => "12345998"
];

$requisicao = new Requisicao('localhost:3001');

$token = $requisicao->obterToken( 'login', 'POST', $dados);
$dados = $requisicao->getOneUser('user', $token, '4');

foreach($dados as $dado) {
    echo $dado . '<br>';
}