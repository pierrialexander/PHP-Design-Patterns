<?php

/**
 * Classe responsável por controlar a abertura e conclusão de uma transação com o banco de dados
 * @since 08/2023
 * @author Pierri Alexander Vidamar
 */
class Transaction 
{
    private static $conn;
    private static $logger;
    private function __construct(){}


    /**
     * Método para abertura da conexão e inicia a transação
     */
    public static function open($database)
    {
        self::$conn = Connection::open($database);
        self::$conn->beginTransaction();
        self::$logger = null;
    }

    /**
     * Método para fechar a conexão e limpa a variável de conexão
     */
    public static function close()
    {
        if (self::$conn) 
        {
            self::$conn->commit();
            self::$conn = null;
        }
    }

    /**
     * Método para obter a conexão atual
     */
    public static function get()
    {
        return self::$conn;
    }

    /**
     * Método para desfazer a conexão iniciada e que não conseguiu concluir
     */
    public static function rollback() 
    {
        if (self::$conn) 
        {
            self::$conn->rollback();
            self::$conn = null;
        }
    }


    /**
     * Método para definir qual o tipo de log deve ser gerado | Xml - TXT - db
     * @param Logger $logger
     * @return void
     */
    public static function setLogger(Logger $logger)
    {
        self::$logger = $logger;
    }

    /**
     * Gera o Log
     * @param [string] $message
     * @return void
     */
    public static function log($message)
    {
        if(self::$logger)
        {
            self::$logger->write($message);
        }
    }
}