<?php

include_once '../lib/DbManager.php';
$val = $_REQUEST['val'];
$db = new DbManager();
$db->OpenDb();
//$refSQL1="AAAAAA";

$refSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_REF,PAYMENT_METHOD FROM memo_archive WHERE MEMO_REF='$val'";
$refObj = find($refSQL);
/*
if ($refObj->MEMO_TYPE=='' || $refObj->MEMO_DATE=='' || $refObj->MEMO_INFO_REF==''){
    
}
else
 * 
 */
    //echo "AAAAAAAAAAAAA";
    echo $refInfo = 'Type: ' . $refObj->MEMO_TYPE . ' Date: ' . $refObj->MEMO_DATE . ' Information Reference: ' . $refObj->MEMO_INFO_REF;
$db->CloseDb();
?>

