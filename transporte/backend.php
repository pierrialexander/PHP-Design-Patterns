<?php
require 'vendor/autoload.php';

header('Content-Type: application/json');

$cidade = $_POST['cidade'];
$estado = $_POST['estado'];

$client = new GuzzleHttp\Client();

$response = $client->request('GET', "https://sandbox.melhorenvio.com.br/api/v2/me/shipment/agencies?company=2country=BR&state={$estado}&city={$cidade}");

$dados = json_decode($response->getBody());

$agencias = [];

foreach ($dados as $agencia)
{
    $agenciaItem = [
        'name' => $agencia->company_name,
        'label' => $agencia->address->label,
        'address' => $agencia->address->address,
        'district' => $agencia->address->district,
        'phone' => $agencia->phone->phone,
        'status' => $agencia->status
    ];
    $agencias[] = $agenciaItem;
}

echo json_encode($agencias);
