<?php
/**
 * Classe de Venda para o m
 * @author Pierri Alexander Vidmar
 */
class Venda {
    
    private $id;
    
    // Array associativo de itens da venda
    private $itens;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id): void {
        $this->id = $id;
    }

    /**
     * Método para adicionar os itens na venda
     * @param float $quantidade
     * @param Produto $produto
     */
    public function addItem($quantidade, Produto $produto)
    {
        $this->itens[] = [ $quantidade, $produto ];
    }
    
    /**
     * Método para retornar os itens da venda
     * @return array $itens
     */
    public function getItens()
    {
        return $this->itens;
    }
}
