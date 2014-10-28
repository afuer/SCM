<?php

include '../lib/DbManager.php';

if ($_GET) {

    $RreqDetailId = getParam('reqDetailId');
    $price = getParam('price');
    $SqlInsertWd = "UPDATE requisition_details SET UNIT_PRICE='$price' WHERE REQUISITION_DETAILS_ID='$RreqDetailId'";
    sql($SqlInsertWd);
} else {
    echo 'not GET';
}
?>

