<?php

/**
 * Classe para excrever um log TXT
 */
class LoggerTXT extends Logger
{
    public function write($message)
    {
        $text = Date('Y-m-d H:i:s') . ' : ' . $message;
        $handler = fopen($this->filename, 'a');
        fwrite($handler, $text . "\n");
        fclose($handler);
    }
}
