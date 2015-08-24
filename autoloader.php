<?php

class Autoloader
{
    public $basePath = __DIR__;
    public $appAlias = 'app';
    public $ext = '.php';
    
    public function autoload($className)
    {
        if (strpos($className, $this->appAlias) !== 0) {
            return;
        }
        $parts = explode('\\', $className);
        array_shift($parts);
        if (empty($parts)) {
            return;
        }
        $filename = $this->basePath . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . $this->ext;
        if (!file_exists($filename)) {
            throw new \ErrorException("Class {$className} is not found");
        }
        include_once $filename;
    }
}