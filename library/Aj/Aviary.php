<?php

class Aj_Aviary
{
    public static function upload($path)
    {
        $aviary = self::getAviaryObject();
        
        $response = $aviary->upload($path);
        
        return isset($response['url']) ? $response['url'] : null;
    }
    
    public static function getAviaryObject()
    {
        $config = Zend_Registry::get("config");
        $aviary = new Aviary_AviaryFx($config->aviary->key, $config->aviary->secret);
        
        return $aviary;
    }
}