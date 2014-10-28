<?php
include 'include.php';
$val = getParam('val');


sql("DELETE FROM gp_requisition_cc_list WHERE REQUISITION_CC_LIST_ID='$val'");
?>