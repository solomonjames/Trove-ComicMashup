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

<img id="comicImage" src="<?php echo $imgUrl?>" />