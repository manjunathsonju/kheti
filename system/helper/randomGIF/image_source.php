<?php
$gifs = array("tomato-loading.gif", "veg.gif", "veg-loading-min.gif", "fruits3098.gif");

$randomGif = $gifs[mt_rand(0, 3)];

$image = file_get_contents($randomGif);
header('Content-type: image/jpeg;');
header("Content-Length: " . strlen($image));
echo $image;
?>