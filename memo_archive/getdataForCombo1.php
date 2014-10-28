<?php

include '../lib/DbManager.php';


$q = isset($_POST['q']) ? strval($_POST['q']) : '';

$db = new DbManager();
$db->OpenDb();

//$rs = mysql_query("SELECT EMPLOYEE_ID, CARD_NO, FIRST_NAME, CONCAT(CARD_NO,'-',FIRST_NAME) AS EM FROM employee WHERE EMPLOYEE_ID like '%$q%' or CARD_NO like '%$q%' or FIRST_NAME like '%$q%'");
$rs = mysql_query("SELECT SUPPLIER_ID, SUPPLIER_NAME, CONCAT(SUPPLIER_ID,'-',SUPPLIER_NAME) AS EM FROM supplier WHERE SUPPLIER_ID like '%$q%' or SUPPLIER_NAME like '%$q%'");

$rows = array();
while ($row = mysql_fetch_assoc($rs)) {
    $rows[] = $row;
}
$db->CloseDb();
echo json_encode($rows);

?>
