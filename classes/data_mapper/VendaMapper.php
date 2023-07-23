<?php
/**
 * Classa Data Mapper responsável por registrar a venda e manipular os itens da venda
 * @author Pierri Alexander Vidmar
 */
class VendaMapper {
    // Atributo estático que irá receber a conexão e disponibilizar para a classe.
    private static $conn;
    
    /**
     * Método que irá receber e setar a conexão disponbilizando para os objetos da classe.
     * @param PDO $conn
     */
    public static function setConnection(PDO $conn) 
    {
        self::$conn = $conn;
    }
    
    /**
     * Método responsável por salvar uma venda na base de dados
     * @param Venda $venda
     */
    public static function save(Venda $venda)
    {
        $data = date('Y-m-d');
        $sql = "INSERT INTO venda(data_venda) values('$data')";
        print $sql . "<br>";
        self::$conn->query($sql);
        
        $id = self::getLastId();
        $venda->setId($id);
        
        // Percorrer cada item da venda
        foreach ($venda->getItens() as $item) 
        {
             $quantidade = $item[0];
             $produto = $item[1];
             $id_produto = $produto->id;
             $preco = $produto->preco;
             
             // GERAMOS UM INSERT PARA CADA ITEM DA VENDA   
             $sql = "INSERT INTO item_venda (id_venda, id_produto, quantidade, preco) values ('{$id}','{$id_produto}','{$quantidade}','{$preco}');";
              
             print $sql . '<br>';
             self::$conn->query($sql);
        }
    }
    
    
    public static function getLastId()
    {
        $sql = "SELECT max(id) as max FROM venda";
        $result = self::$conn->query($sql);
        $data =  $result->fetchObject();
        return $data->max;
    }
    
}
