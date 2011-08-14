 <script type="text/javascript"> 
    var _featherLoaded = false; 
     
    Feather_APIKey = '8b863931d'; 
    Feather_Theme = 'bluesky'; 
    Feather_EditOptions = 'stickers,text'; 
    Feather_OpenType = 'float'; 
    Feather_CropSizes = '320x240,640x480,800x600,1280x1024'; 
    Feather_Stickers = [ 
[ 'http://www.brianstoys.com/store/images/products/Star%20Wars/MasterReplica/FXSaber/MasterReplica_YodaFX.gif', 'http://www.aviary.com/images/feather/sticker/bandaid_01.png' ]];
      
    Feather_OnSave = function(id, url) { 
        var e = document.getElementById(id); 
        e.src = url; 
        aviaryeditor_close(); 
    } 
     
    Feather_OnLoad = function() { 
        _featherLoaded = true; 
    } 
     
    function launchEditor(imageid) { 
        if (_featherLoaded) { 
            var src = document.getElementById(imageid).src; 
            aviaryeditor(imageid, src); 
        } 
    }

    
</script> 
<script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>  
 
<!-- End Generated Code -- Don't Copy Below This Line --> 
<?php

require_once dirname(__FILE__) . "/config/boot.php";
$max = 49;
mt_srand(time());

$one = mt_rand(0, $max);
$two = mt_rand(0, $max);
$three = mt_rand(0, $max);

if($one==$two) {
	$two += 1;
}
if($one==$three) {
	$three += 1;
}

if($three==$two) {
	$two += 1;
}


$trove = new Trove();
$trove_images = json_decode($trove->get('/v2/content/photos/', array('count'=>$max)), true);

$images = array();
$images[] = $trove_images['results'][$one]['urls']['original'];
$images[] = $trove_images['results'][$two]['urls']['original'];
$images[] = $trove_images['results'][$three]['urls']['original'];

foreach ($images as $image) {
	$img_name = md5($image) . '.png';
	
	if(!file_exists(BP . '/ims/' . $img_name)) {
    	$img = Aj_Aviary_ImageTransform::getComic($image);
	}
    file_put_contents(BP . '/imgs/' . $img_name,CurlUtil::get($img));
    $loaded_images[] = BP . '/imgs/' . $img_name;
    
}

$imgUrl = Aj_Image::createComicStrip($loaded_images);

?>

<img onclick="launchEditor('comicImage')" id="comicImage" src="<?php echo $imgUrl?>" />
