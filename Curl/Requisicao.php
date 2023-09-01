<?php

class Requisicao {

    private string $urlBase;
    private string $token;
    private array $dados;

    public function __construct($urlBase) {
        $this->urlBase = $urlBase;
    }

    public function getToken()
    {
        return $this->token;
    }
    
    public function obterToken($uri = '', $method = 'GET', $data = []) {
        $curl = curl_init();

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        curl_setopt($curl, CURLOPT_URL, $this->urlBase . "/" . $uri);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $requisicao = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($requisicao, false);
        
        $this->token = $data->token;
        
        return $this->token;
    }


    public function getOneUser($uri, $token = '', $id = '1') {
        $curl = curl_init();

        $headers = [
            'Authorization: Bearer ' . $token,
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $this->urlBase . "/" . $uri . '/' . $id);

        $data = curl_exec($curl);

        curl_close($curl);

        $user = json_decode($data, true);

        $this->dados = $user;

        return $this->dados;
    }

    /**
     * Busca Ocorrências Azul Cargo
     * @param [type] $chaveNota
     * @return void
     */
    public function buscaRastreioAzul($chaveNota)
    {
        $curl = curl_init();

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $this->urlBase . "/api/Ocorrencias/Consultar?Token=" . $this->token . '&chaveNFE=' . $chaveNota);

        $data = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($data, true);
        
        $this->dados = $response;

        return $this->dados;
    }

}