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
    if (!isset($_SESSION['loaded_images'][md5($image)])) {
        $_SESSION['loaded_images'][md5($image)] = Aj_Aviary_ImageTransform::getComic($image);
    }
}

$loaded_images = isset($_SESSION['loaded_images']) && !empty($_SESSION['loaded_images']) ? $_SESSION['loaded_images'] : array();
?>

<?php foreach ($loaded_images as $imgUrl):?>
<img src="<?php echo $imgUrl?>" />
<?php endforeach;?>