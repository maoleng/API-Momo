<?php

// Require database & thông tin chung
require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/core/init.php');
 
// Xoá session
$session->destroy();
load_url('/'); 
?>