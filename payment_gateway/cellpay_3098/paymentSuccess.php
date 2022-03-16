<?php

$req_dump = print_r($_REQUEST, true);
$fp = file_put_contents('cellpay_log_3099.log', $req_dump, FILE_APPEND);

?>