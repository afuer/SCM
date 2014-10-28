<?php

include '../lib/DbManager.php';
$db = new DbManager();
$db->OpenDb();
$val = getParam('val');


sql("DELETE FROM file_attach_list WHERE FILE_ATTACH_LIST_ID='$val'");

$db->CloseDb();
?>