<?php
namespace Frame\Database;
use \PDO;
use \Exception;

/**
 * Classe Connection é responsável por estabelecer conexões com diferentes tipos de bancos de dados,
 * como PostgreSQL, MySQL e SQLite. Ela fornece um método estático para abrir conexões com base em configurações
 * definidas em arquivos INI. Esta classe é parte do framework para manipulação de bancos de dados e transações.
 * @author Pierri Alexander Vidmar
 * @since 08/2023
 */
class Connection {
    private function __construct() {}
    
    public static function open($file)
    {
        if(file_exists("config/{$file}.ini"))
        {
            $db = parse_ini_file("config/{$file}.ini");
        }
        else {
            throw new Exception("Arquivo {$file} não encontrado!");
        }
        
        $user = isset($db['user']) ? $db['user'] : null;
        $pass = isset($db['pass']) ? $db['pass'] : null;
        $name = isset($db['name']) ? $db['name'] : null;
        $host = isset($db['host']) ? $db['host'] : null;
        $type = isset($db['type']) ? $db['type'] : null;
        $port = isset($db['port']) ? $db['port'] : null;
        
        switch ($type) {
            case 'pgsql':
                $port = isset($db['port']) ? $db['port'] : '5432';
                $conn = new PDO("pgsql:dbname={$name}; user={$user}; password={$pass}; host={$host}; port={$port}");
                break;
            case 'mysql':
                $port = isset($db['port']) ? $db['port'] : '3306';
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
                break;
            case 'sqlite':
                $conn = new PDO("sqlite:{$name}");
                break;
        }
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}
