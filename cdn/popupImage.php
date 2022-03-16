<?php
$random_image = rand(1,3) . ".jpeg";
$url = "https://khetifood.com/image/popupImages/" . $random_image;
//echo $url;die;
$image = file_get_contents($url);
header('Content-type: image/jpeg;');
header("Content-Length: " . strlen($image));
echo $image;
?>