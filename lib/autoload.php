<?php

/**
 * Autoloader class
 *
 * @package SimplePie
 * @subpackage API
 */
class SimplePie_Autoloader
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->path = dirname(__FILE__);
    }

    /**
     * Autoloader
     *
     * @param string $class The name of the class to attempt to load.
     */
    public function autoload($class)
    {
        // Only load the class if it starts with "SimplePie"
        if (strpos($class, 'SimplePie') !== 0)
        {
            return;
        }

        $filename = $this->path . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        if (file_exists($filename)) {
            require_once($filename);
        }
        else {
            return;
        }
    }
}

spl_autoload_register(array(new SimplePie_Autoloader(), 'autoload'));
