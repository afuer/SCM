<?php

include '../lib/DbManager.php';

$q = isset($_POST['q']) ? strval($_POST['q']) : '';

$db = new DbManager();
$db->OpenDb();

$rs = mysql_query("SELECT EMPLOYEE_ID, CARD_NO, FIRST_NAME, CONCAT(CARD_NO,'-',FIRST_NAME) AS EM FROM employee WHERE EMPLOYEE_ID like '%$q%' or CARD_NO like '%$q%' or FIRST_NAME like '%$q%' LIMIT 0,500");
$rows = array();
while ($row = mysql_fetch_assoc($rs)) {
    $rows[] = $row;
}

$db->CloseDb();
echo json_encode($rows);
?>
