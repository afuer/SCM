<?php
include 'include.php';
$val = getParam('val');


sql("DELETE FROM gp_requisition_file_attach_list WHERE REQUISITION_FILE_ATTACH_LIST_ID='$val'");
?>