<?php

session_start();
header("Cache-Control: private, no-cache, must-revalidate");

require("library.php");

$request = array_merge($_POST, $_GET);
$form_id = $request["form_id"];

load_form($form_id);

?>