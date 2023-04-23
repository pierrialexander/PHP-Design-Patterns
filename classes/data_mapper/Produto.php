<?php
/**
 * Classe do padrão Data Mapper de Produto
 * @author Pierri Alexander Vidmar
 */
class Produto {
    
    // vetor que armazenará os dados que serão persistidos na base.
    private $data;

    public static function setConnection(PDO $conn)
    {
        self::$conn = $conn;
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
}
