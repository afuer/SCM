<?php
include '../lib/DbManager.php';

$card_no = getParam('card_no');
echo $employeeDetails = GetEmployeeDetails($card_no);
?>


