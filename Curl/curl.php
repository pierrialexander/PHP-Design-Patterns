<?php
require_once 'Requisicao.php';

$dados = [
    "identificacaoUsuario" => "atendimento02@upmobilia.com.br",
	"password" => "9onBxF8a"
];

$requisicao = new Requisicao('https://ediapi.onlineapp.com.br');

$token = $requisicao->obterToken( 'apicoletor/api/Autenticacao', 'POST', $dados);

//echo $requisicao->getToken();

var_dump($requisicao->buscaRastreioAzul('42221037806837000132550010000068311000145353'));