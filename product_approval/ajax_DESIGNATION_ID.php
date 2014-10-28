<?php
include '../lib/DbManager.php';
$id= getParam('val');

$desigListSQL=rs2array(query("SELECT DESIGNATION_ID, DESIGNATION_NAME FROM designation WHERE LOCATION_NAME='$id'"));
comboBox('DESIGNATION_ID', $desigListSQL, $selectedValue, FALSE);

?>
