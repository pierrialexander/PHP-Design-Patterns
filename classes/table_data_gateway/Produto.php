<?php

/**
 * Classe de domínio/negócio Produto. Responsável por gerenciaar e chamar o gateway de produtos
 * para este estar fazendo chamadas de operações de persistencia. Opera as regras de negócio e cálculos.
 * @author Pierri Alexander Vidmar
 * @since 02/04/2023
 */
class Produto
{
    // vetor que armazenará os dados que serão persistidos na base.
    private $data;

    public static function setConnection(PDO $conn)
    {
        ProdutoGateway::setConnection($conn);
    }

    /**
     * Método mágico para obter o dado solicitado do vetor de dados.
     * @param $prop
     * @return mixed
     */
    public function __get($prop)
    {
        return $this->data[$prop];
    }

    /**
     * Método mágico que irá setar no array de dados a posição informada.
     * @param $prop
     * @param $value
     * @return void
     */
    public function __set($prop, $value)
    {
        $this->data[$prop] = $value;
    }

    public static function find($id)
    {
        $gw = new ProdutoGateway();
        return $gw->find($id, 'Produto');
    }

    public static function all($filter = '')
    {
        $gw = new ProdutoGateway();
        return $gw->all($filter, 'Produto');
    }

    public function delete()
    {
        $gw = new ProdutoGateway();
        return $gw->delete($this->id);
    }

    public function save()
    {
        $gw = new ProdutoGateway();
        return $gw->save((object) $this->data);
    }

    public function getMargemLucro()
    {
        return (($this->preco_venda - $this->preco_custo) / $this->preco_custo) * 100;
    }

    public function registraCompra($custo, $quantidade)
    {
        $this->preco_custo = $custo;
        $this->estoque += $quantidade;
    }
}