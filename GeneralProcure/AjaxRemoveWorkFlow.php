<?php
include 'include.php';
$val = getParam('val');


sql("DELETE FROM gp_requisition_flow_list WHERE GP_REQUISITION_FLOW_LIST_ID ='$val'");
?>