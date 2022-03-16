<?php
$size = "376x314";
$url = "https://placehold.it/" . $size;
$image = file_get_contents("http://demo3098output.kheti.farm/image/whatsapp/WhatsApp%20Image%202020-05-31%20at%2010.31.36%20AM.jpeg");
header('Content-type: image/jpeg;');
header("Content-Length: " . strlen($image));
echo $image;
?>