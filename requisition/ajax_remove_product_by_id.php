<?php

include '../lib/DbManager.php';
$val = getParam('val');

$db = new DbManager();
$db->OpenDb();
sql("DELETE FROM requisition_details WHERE REQUISITION_DETAILS_ID='$val'");
$db->CloseDb();
?>