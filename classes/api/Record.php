<?php

/**
 * Classe Record que será base para métodos de persistência para outras classes
 */
abstract class Record
{
    // Vetor para armazenar os dados que serão persistidos
    protected $data;


    public function __construct($id = null)
    {
        if($id)
        {
            $object = $this->load($id);
            if($object)
            {
                $this->fromArray($object->toArray());
            }
        }
    }

    public function __set($prop, $value)
    {
        if($value === NULL)
        {
            unset($this->data[$prop]);
        }
        else
        {
            $this->data[$prop] = $value;
        }
    }

    public function __get($prop)
    {
        if(isset($this->data[$prop]))
        {
            return $this->data[$prop];
        }
    }

    public function __isset($prop)
    {
        return isset($this->data[$prop]);
    }

    /**
     * Quando feito um clone do objeto, não copia o Id que é a chave única do outro objeto.
     * @return void
     */
    public function __clone()
    {
        unset($this->data['id']);
    }

    /**
     * Alimenta um objeto com base em dados vindos de um array
     * @param [type] $data
     * @return void
     */ 
    public function fromArray($data)
    {
        $this->data = $data;
    }

    /**
     * Exporta o objeto em forma de vetor
     * @return void
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Método auxiliar para obter a constante com o nome da classe e tabela atual.
     * @return void
     */
    public function getEntity()
    {
        $class = get_class($this);
        return constant("{$class}::TABLENAME");
    }

    /**
     * Lê um objeto da base de dados
     * @return void
     */
    public function load($id)
    {
        $sql = "SELECT * FROM {$this->getEntity()} WHERE id = " . (int) $id;

        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            $result = $conn->query($sql);

            if($result)
            {
                $result->fetchObject(get_class($this));
            }

        }
        else 
        {
            throw new Exception("Não há transação ativa!");
        }
    }

    /**
     * Grava os dados na base
     * @return void
     */
    public function store()
    {
        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            $result = $conn->query($sql);
        }
        else 
        {
            throw new Exception("Não há transação ativa!");
        }
    }
    
    /**
     * Exclui uma registro da base
     * @return void
     */
    public function delete()
    {
        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            $result = $conn->query($sql);
        }
        else 
        {
            throw new Exception("Não há transação ativa!");
        }
    }


}