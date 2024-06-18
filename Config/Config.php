<?php

namespace Config;

use Exception;
use Dotenv\Dotenv;

/**
 * Config class
 *
 * This class is a singleton class that holds configuration values.
 */
class Config extends \Utils\Singleton
{

    /**
     * @var array $config
     *
     * This array holds the configuration values.
     */
    protected static $instance;
    protected $config = [];

    private function __construct()
    {
        self::$instance = $this;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get a configuration value.
     *
     * @param string $name
     *
     * @return mixed
     *
     * @throws Exception if the property is not set
     */
    public function get($name)
    {
        $value = $_ENV[$name];
        if ($value === false) {
            throw new Exception("Property $name is not set.");
        }
        return $value;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Load configuration values.
     *
     * Adds a .env file to the configuration values.
     */
    public function load(string $folder)
    {
        if(!is_readable($folder)){
            throw new Exception("Configuration folder $folder not found.");
        }

        $dotenv = Dotenv::createImmutable($folder);
        $dotenv->load();

        return $this;
    }
}
