<?php

require_once dirname(__FILE__) . "/config/boot.php";

$images = array();
$images[] = "https://fbcdn-sphotos-a.akamaihd.net/photos-ak-ash1/v107/180/108/5114964/n5114964_35467730_3958.jpg";
$images[] = "https://fbcdn-sphotos-a.akamaihd.net/photos-ak-ash1/v119/180/108/5114964/n5114964_35733244_5112.jpg";
$images[] = "https://fbcdn-sphotos-a.akamaihd.net/hphotos-ak-ash4/222349_10100354940593052_5114964_56609009_5185551_n.jpg";

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