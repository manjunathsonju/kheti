<?php

$req_dump = print_r($_REQUEST, true);
$fp = file_put_contents('failure_request.log', $req_dump, FILE_APPEND);

header("Location: https://khetifood.com/index.php?route=checkout/failure");
?>