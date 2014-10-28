<?php
include 'include.php';
$val = getParam('val');


sql("DELETE FROM gp_requesiton_details WHERE REQUISITION_DETAILS_ID='$val'");
?>