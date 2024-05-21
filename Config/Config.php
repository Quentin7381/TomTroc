<?php

namespace Config;

/**
 * Config class
 *
 * This class is a singleton class that holds configuration values.
 */
class Config extends Singleton {
    
    /**
     * @var array $config
     * 
     * This array holds the configuration values.
     */
    protected $config = [];

    /**
     * @see Config::get()
     */
    public function __get($name){
        return $this->get($name);
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
    public function get($name){
        if(!isset($this->config[$name])){
            throw new Exception('This property is not set!');
        }
        return $this->config[$name];
    }

    /**
     * @see Config::set()
     */
    public function __set($name, $value){
        return $this->set($name, $value);
    }

    /**
     * Set a configuration value.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return Config
     *
     * @throws Exception if the property is already set
     */
    public function set($name, $value){
        if(isset($this->config[$name])){
            throw new Exception('This property is already set!');
        }
        $this->config[$name] = $value;
        return $this;
    }

    /**
     * Override a configuration value.
     * This method will not throw an exception if the property is already set.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return Config
     */
    public function override($name, $value){
        $this->config[$name] = $value;
        return $this;
    }

    /**
     * Load configuration values.
     *
     * @param array $config
     * @param bool $override = false If true, overlapping values will be overridden with the new values.
     *
     * @throws Exception if the property is already set and $override is false
     * @return Config
     */
    public function load(array $config, bool $override = false){
        if($override){
            foreach($config as $key => $value){
                $this->override($key, $value);
            }
        } else {
            foreach($config as $key => $value){
                $this->set($key, $value);
            }
        }
        
        return $this;
    }
}
