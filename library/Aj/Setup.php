<?php

class Aj_Setup
{
    public static function session()
    {
        Zend_Session::setOptions(array(
            'cookie_lifetime' => 0,
            'cookie_path'     => "/",
            //'cookie_domain'   => ".dashron.com",
            'cookie_secure'   => false,
            'cookie_httponly' => true
        ));

        switch (true) {
            case !empty($_REQUEST['PHPSESSID']):
                Zend_Session::setId($_REQUEST['PHPSESSID']);
                break;
        }
        
        Zend_Session::start();
    }
}