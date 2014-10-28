<?php

include '../lib/DbManager.php';

$val = getParam('val');


$categorySubList = rs2array(query("SELECT CATEGORY_SUB_UNDER_ID, CATEGORY_SUB_UNDER_NAME FROM category_sub_under WHERE CATEGORY_SUB_ID='$val' ORDER BY CATEGORY_SUB_UNDER_NAME"));

comboBox('UNDER_SUB_CATEGORY_ID', $categorySubList, NULL, TRUE);
?>
