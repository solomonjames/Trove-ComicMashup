<?php

class Aj_Image
{
    public static function createComicStrip(array $images)
    {
        $output = imagecreatetruecolor(900, 400);
        $s_x    = 0;
        
        for ($i = 0; $i < 3; $i++) {
            $contents = imagecreatefrompng($images[$i]);
            
            imagecopy($output, $contents, $s_x, 0, 0, 0, 300, 400);
            
            $s_x = $s_x + 300;
            imagedestroy($contents); 
        }
        
        $relPath  = "/generated/" . md5(implode("", $images));
        $fullPath = BP . $relPath . ".png";
        
        imagepng($output, $fullPath);
        imagedestroy($output); 
        
        return $relPath;
    }
}