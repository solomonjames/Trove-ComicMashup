<?php

/**
 * 
 * @author James Solomon <solomonjames@gmail.com>
 */
class Aj_Ajax_Response
{
    protected $_data;
    
    protected $_isJsonp = false;
    
    public function __construct($data = array())
    {
        $this->_data = $data;
    }
    
    /**
     * Direct
     * 
     * @param  array $data
     * @return Aj_Ajax_Response
     */
    public static function direct($data = array())
    {
        return new self($data);
    }
    
    public function setJsonp()
    {
        $this->_isJsonp = true;
        return $this;
    }
    
    /**
     * Sets headers and returns encoded data
     * 
     * @return string Json encoded string
     */
    public function send()
    {
        $this->_setHeaders();
        $json = json_encode($this->_data);
        
        if ($this->_isJsonp) {
            $json = $_GET['callback'] . "(" . $json . ");";
        }
        
        return $json;
    }
    
    /**
     * Will set headers, echo json and exit script
     * 
     * @return void
     */
    public function sendAndExit()
    {
        echo $this->send();
        exit;
    }
    
    /**
     * Sets json related headers
     * 
     * @return void
     */
    protected function _setHeaders()
    {
        if (!headers_sent()) {
            header("Content-Type: application/json");
        }
    }
}