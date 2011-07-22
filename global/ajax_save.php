<?php

session_start();
header("Cache-Control: private, no-cache, must-revalidate");

require("library.php");

$request = array_merge($_POST, $_GET);
$request = clean_hash($request);

$account_id = $_SESSION["account_id"];
$form_name    = addslashes($request["form_name"]);
$form_content = addslashes($request["form_content"]);

save_form($account_id, $form_name, $form_content);

?>