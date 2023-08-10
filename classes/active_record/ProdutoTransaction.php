<?php

/**
 * Classe active record Produto
 */
class ProdutoTransaction
{
    // vetor que armazenará os dados que serão persistidos na base.
    private $data;

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
        $conn = Transaction::get();
        $sql = $conn->prepare("SELECT * FROM produto WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        return $sql->fetchObject( __CLASS__ );
    }

    public static function all($filter = '')
    {
        $sql = "SELECT * FROM produto ";
        if($filter) {
            $sql .= " WHERE $filter";
        }
        print " $sql <br>";

        $conn = Transaction::get();
        $result = $conn->query($sql);
        return $result->fetchAll(PDO::FETCH_CLASS, __CLASS__);
    }

    public function delete()
    {
        $sql = "DELETE FROM produto WHERE id = {$this->id}";
        print " $sql <br>";

        $conn = Transaction::get();
        return $conn->query($sql);
    }

    public function save()
    {
        // Se nos ddos enviados NÃO CONSTAR O ID, faz a inserção.
        if (empty($this->data['id'])) {
            $id = $this->getLastId() + 1;
            $sql = "INSERT INTO produto (id, descricao, estoque, preco_custo, "
                . " preco_venda, codigo_barras, data_cadastro, origem) "
                . " VALUES ('{$id}', "
                . "'{$this->descricao}', "
                . "'{$this->estoque}', "
                . "'{$this->preco_custo}', "
                . "'{$this->preco_venda}', "
                . "'{$this->codigo_barras}', "
                . "'{$this->data_cadastro}', "
                . "'{$this->origem}')";
        }
        else {
            $sql = "UPDATE produto SET descricao        = '{$this->descricao}', "
                . " estoque         = '{$this->estoque}', "
                . " preco_custo     = '{$this->preco_custo}', "
                . " preco_venda     = '{$this->preco_venda}', "
                . " codigo_barras   = '{$this->codigo_barras}', "
                . " data_cadastro   = '{$this->data_cadastro}', "
                . " origem          = '{$this->origem}' "
                . " WHERE id            = '{$this->id}'";
        }
        print "$sql <br>";

        $conn = Transaction::get();
        return $conn->exec($sql);
    }

    public function getLastId()
    {
        $sql = "SELECT max(id) as max FROM produto";
        
        $conn = Transaction::get();
        $result = $conn->query($sql);
        $data =  $result->fetchObject();
        return $data->max;
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