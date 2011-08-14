<?php
require_once dirname(__FILE__) . "/config/boot.php";

$trove = new Trove();
$images = json_decode($trove->get('/v2/content/photos/', array('count'=>25)), true);