<?php

include '../lib/DbManager.php';

if ($_GET) {

    $searchId = getParam('searchId');

    $SqlInsertCcLis = "DELETE FROM requisition_cc_list WHERE REQUISITION_CC_LIST_ID='$searchId'";
    $result = sql($SqlInsertCcLis);

    if ($result) {
        echo json_encode(array('success' => true, 'Id' => $searchId));
    } else {
        echo json_encode(array('msg' => 'Some errors occured.'));
    }
}
?>

