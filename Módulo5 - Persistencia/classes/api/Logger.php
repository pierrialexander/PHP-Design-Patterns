<?php

/**
 * Classe abstrata de Logger, na qual classes expecíficas de Log devem ser extendidas para criar logs de formatos expecíficos
 */
abstract class Logger
{
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        file_put_contents($filename, '', FILE_APPEND);
    }

    abstract function write($message);
}