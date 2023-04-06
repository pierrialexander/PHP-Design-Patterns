<?php

/**
 * Classe de persistência de Produtos, responsável por fazer as operações SQL com a base de dados.
 * @author Pierri Alexander Vidmar
 * @since 02/04/2023
 */
class ProdutoGateway
{

    private static $conn;

    /**
     * Nossa classe não irá estabelecer uma conexão, mas sim, sempre irá receber uma conexão PDO.
     * Posteriormente disponibilizará na classe através do atributo estático $conn;
     * @param PDO $conn
     * @return void
     */
    public static function setConnection(PDO $conn)
    {
        self::$conn = $conn;
    }


    /**
     * Retorna um único registro conforme ID informado.
     * @param $id
     * @param $class
     * @return mixed
     */
    public function find($id, $class = 'stdClass')
    {
        $sql = self::$conn->prepare("SELECT * FROM produto WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        return $sql->fetchObject($class);
    }

    /**
     * Busca e retorna um conjunto de registros conforme tipo informado por parâmetro.
     * Pode ser informado um filtro ou não.
     * @param $filter
     * @param $class
     * @return mixed
     */
    public function all($filter = null, $class = 'stdClass')
    {
        $sql = "SELECT * FROM produto ";
        if($filter) {
            $sql .= " WHERE $filter";
        }
        print " $sql <br>";
        $result = self::$conn->query($sql);
        return $result->fetchAll(PDO::FETCH_CLASS, $class);
    }

    /**
     * Exclui um único registro baseado no ID informado.
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $sql = "DELETE FROM produto WHERE id = $id";
        print " $sql <br>";
        return self::$conn->query($sql);
    }

    /**
     * Recebe um vetor de dados e grava estes no banco de dados.
     * @param array $data
     * @return void
     */
    public function save($data)
    {
        // Se nos ddos enviados NÃO CONSTAR O ID, faz a inserção.
        if (empty($data->id)) {
            $id = $this->getLastId() + 1;
            $sql = "INSERT INTO produto (id, descricao, estoque, preco_custo, "
                                        . " preco_venda, codigo_barras, data_cadastro, origem) "
                                        . " VALUES ('{$id}', "
                                                . "'{$data->descricao}', "
                                                . "'{$data->estoque}', "
                                                . "'{$data->preco_custo}', "
                                                . "'{$data->preco_venda}', "
                                                . "'{$data->codigo_barras}', "
                                                . "'{$data->data_cadastro}', "
                                                . "'{$data->origem}')";
        }
        else {
            $sql = "UPDATE produto SET descricao        = '{$data->descricao}', "
                                    . " estoque         = '{$data->estoque}', "
                                    . " preco_custo     = '{$data->preco_custo}', "
                                    . " preco_venda     = '{$data->preco_venda}', "
                                    . " codigo_barras   = '{$data->codigo_barras}', "
                                    . " data_cadastro   = '{$data->data_cadastro}', "
                                    . " origem          = '{$data->origem}' "
                                . " WHERE id            = '{$data->id}'";
        }
        print "$sql <br>";
        return self::$conn->exec($sql);
    }

    /**
     * Retorna o último ID da tabela
     * @return mixed
     */
    public function getLastId()
    {
        $sql = "SELECT max(id) as max FROM produto";
        $result = self::$conn->query($sql);
        $data =  $result->fetchObject();
        return $data->max;
    }
}