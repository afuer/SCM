<?php
include_once '../lib/DbManager.php';
$searchID= $_REQUEST ['id'];

$totRebateAmount = findValue("SELECT REBATE_PERCENTAGE FROM gl_account WHERE GL_ACCOUNT_ID='$searchID'");


echo $totRebateAmount;

?>
