<?php

include '../lib/DbManager.php';
$db = new DbManager();
$val = getParam('val');
$db->OpenDb();

$categorySubList = rs2array(query("SELECT CATEGORY_SUB_ID, CATEGORY_SUB_NAME FROM category_sub WHERE CATEGORY_ID='$val' ORDER BY CATEGORY_SUB_NAME"));
$db->CloseDb();
comboBox('CATEGORY_SUB_ID', $categorySubList, NULL, TRUE, '', 'ajax_category_under_sub_id_search');
?>
