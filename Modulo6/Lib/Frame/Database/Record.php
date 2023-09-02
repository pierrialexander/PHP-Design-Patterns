<?php
namespace Frame\Database;
use Exception;
/**
 * Classe Record é uma classe abstrata do Framework que serve como base para a implementação de métodos de persistência em outras classes.
 * Ela fornece funcionalidades comuns para a manipulação de registros de banco de dados, como carregar, salvar,
 * atualizar e excluir registros, além de fornecer métodos para manipular os dados associados a um registro.
 * @author Pierri Alexander Vidmar
 * @since 01/09/2023
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
                return $result->fetchObject(get_class($this));
            }

        }
        else 
        {
            throw new Exception("Não há transação ativa!");
        }
    }

    /**
     * Grava os dados na base, verifica se existe existe ID.
     * Se sim, atualiza, se não cria. Preparando os dados de forma segura para se executado no banco.
     * @return void
     */
    public function store()
    {
        if(empty($this->data['id']) or (!$this->load($this->data['id'])))
        {

            $prepared = $this->prepare($this->data);

            if(empty($this->data['id'])) 
            {
                $this->data['id'] = $this->getLast() + 1;
                $prepared['id'] = $this->data['id'];
            }
            $sql = "INSERT INTO {$this->getEntity()}" . 
                   " (" . implode(', ', array_keys($prepared)) . ") " .
                   " VALUES "     .
                   " (" . implode(', ', array_values($prepared)) . ") ";
        }
        else
        {
            $prepared = $this->prepare($this->data);
            $set = [];
            
            foreach($prepared as $column => $value) 
            {
                $set[] = "$column = $value";
            }

            $sql = "UPDATE {$this->getEntity()}";
            $sql.= " SET ". implode(', ', $set);
            $sql.= " WHERE id = ". (int) $this->data['id'];

        }
        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            return $conn->exec($sql);
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
    public function delete($id = null)
    {
        $id = $id ? $id : $this->data['id'];


        $sql = "DELETE FROM {$this->getEntity()} WHERE ID = " . (int) $id;

        if ($conn = Transaction::get())
        {
            Transaction::log($sql);
            return $conn->query($sql);
        }
        else 
        {
            throw new Exception("Não há transação ativa!");
        }
    }


    /**
     * Retorna um registro correspondente ao ID na tabela associada à classe.
     * @param int $id
     * @return void
     */
    public static function find($id)
    {
        $classname = get_called_class();
        $ar = new $classname;
        return $ar->load($id);
    }


    public function getLast() 
    {        
        if ($conn = Transaction::get())
        {
            $sql = "SELECT max(id) FROM {$this->getEntity()}";

            Transaction::log($sql);
            $result = $conn->query($sql);
            $row = $result->fetch();
            return $row[0];
        }
        else 
        {
            throw new Exception("Não há transação ativa!");
        }
    }


    /**
     * Prepara um array de dados para serem usados em operações com o banco de dados 
     * @param [type] $data
     * @return void
     */
    public function prepare($data)
    {
        $prepared = array();
        foreach($data as $key => $value) {
            if(is_scalar($value)) {
                $prepared[$key] = $this->escape($value);
            }
        }
        return $prepared;
    }

    /**
     * Escapa e formata um valor para ser usado de forma segura em consultas ou saídas do banco de dados.
     * @param [type] $value
     * @return void
     */
    public function escape($value)
    {
        if(is_string($value) and (!empty($value))) {
            // adiciona \ em aspas
            $value = addslashes($value);
            return "'$value'";
        }
        else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }
        else if ($value !== '') {
            return $value;
        }
        else {
            return "NULL";
        }
    }



}