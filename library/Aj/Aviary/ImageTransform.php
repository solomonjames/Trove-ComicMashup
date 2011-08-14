<?php

class Aj_Aviary_ImageTransform
{
    public static function getComic($imageUrl)
    {
        $aviary = Aj_Aviary::getAviaryObject();
        
        $backgroundColor = "";
        $format          = "png";
        $quality         = "100";
        $scale           = "1";
        $width           = "300";
        $height          = "400";
        
        $renderParameters = array(
            "parameter" => array(
                 array("id" => "Color Count", "value" => "12" ),
                 array("id" => "Saturation", "value" => "1" ),
                 array("id" => "Curve Smoothing", "value" => "4" ),
                 array("id" => "Posterization", "value" => "12" ),
                 array("id" => "Pattern Channel", "value" => "7" ),
                 array("id" => "Pattern Threshold", "value" => "100" ),
                 array("id" => "Pattern Scale", "value" => "1" ),
                 array("id" => "Pattern Angle", "value" => "0.3" ),
                 array("id" => "Pattern Type", "value" => "0" )
            )
        );
        
        $response = $aviary->render($backgroundColor, $format, $quality, $scale, $imageUrl, "20", $width, $height, $renderParameters);
        
        return isset($response['url']) ? $response['url'] : null;
    }
}