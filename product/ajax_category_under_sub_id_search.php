<?php

include '../lib/DbManager.php';
$db = new DbManager();
$val = getParam('val');
$db->OpenDb();

$categorySubList = rs2array(query("SELECT CATEGORY_SUB_UNDER_ID, CATEGORY_SUB_UNDER_NAME FROM category_sub_under WHERE CATEGORY_SUB_ID='$val' ORDER BY CATEGORY_SUB_UNDER_NAME"));
$db->CloseDb();
comboBox('CategoryUnderSubId', $categorySubList, NULL, TRUE, '', '');
?>
