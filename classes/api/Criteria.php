<?php

class Criteria 
{

    private $filters;
    private $properties;

    public function __construct()
    {
        $this->filters = [];
    }

    /**
     * Método Responsável por adicionar os filtros
     * @param [type] $variable
     * @param [type] $compare
     * @param [type] $value
     * @param string $logic_op
     * @return void
     */
    public function add($variable, $compare, $value, $logic_op = 'and')
    {
        if(empty($this->filters))
        {
            $logic_op = null;
        }
        $this->filters[] = [$variable, $compare, $this->transform($value), $logic_op];
    }

    /**
     * Método Responsável por transformar os valores antes de ser passado para o vetor de filtro e que seja aceito no SQL
     * @param [type] $value
     * @return void
     */
    public function transform($value) 
    {
        // SE FOR ARRAY
        if(is_array($value))
        {
            foreach($value as $x)
            {
                if(is_integer($x))
                {
                    $foo[] = $x;
                }
                else if (is_string($x))
                {
                    $foo[] = "'$x'";
                }
            }
            $result = '(' . implode(',', $foo) . ')';
        }
        // SE FOR STRING
        else if(is_string($value))
        {
            $result = "'$value'";
        }
        // SE FOR NULO
        else if(is_null($value))
        {
            $result = 'NULL';
        }
        // SE FOR BOOL
        else if(is_bool($value))
        {
            $result = $value ? 'TRUE' : 'FALSE';
        }
        else {
            $result = $value;
        }
        // RETORNA O VALOR
        return $result;
    }

    /**
     * Método responsável por retornar os filtros do vetor em formatos de string
     * @return void
     */
    public function dump()
    {
        if(is_array($this->filters) and count($this->filters) > 0)
        {
            $result = '';
            foreach ($this->filters as $filter)
            {
                $result .= $filter[3] . ' ' . $filter[0] . ' ' . $filter[1] . ' ' . $filter[2] . ' ';
            }
            $result = trim($result);
            return "({$result})";
        }
    }

    /**
     * Método para definir uma propriedade
     * @param [type] $property
     * @param [type] $value
     * @return void
     */
    public function setProperty($property, $value)
    {
        $this->properties[$property] = $value;
    }

    /**
     * Método para retornar uma propriedade
     * @param [type] $property
     * @return void
     */
    public function getProperty($property)
    {
        if(isset($this->properties[$property]))
        {
            return $this->properties[$property];
        }
    }
}