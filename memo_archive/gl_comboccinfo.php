<?php

include '../lib/DbManager.php';

$query = getParam('q');

$query = $query == '' ? '%' : "%$query%";
//$q = isset($_POST['q']) ? strval($_POST['q']) : '';
//$db = new DbManager();
//$db->OpenDb();

$rs = query("SELECT GL_ACCOUNT_ID, GL_ACCOUNT_NAME, REBATE_PERCENTAGE
FROM gl_account gl
WHERE GL_ACCOUNT_ID LIKE '%$query%' OR GL_ACCOUNT_NAME LIKE '%$query%'
ORDER BY GL_ACCOUNT_NAME");


/*
 * $rs = query("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, dn.DIVISION_NAME, CONCAT(COST_CENTER_CODE,'-',COST_CENTER_NAME,'-',DIVISION_NAME) AS CC FROM cost_center cc
  LEFT OUTER JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID WHERE cc.COST_CENTER_CODE like '$query'
  OR cc.COST_CENTER_NAME like '$query' OR dn.DIVISION_NAME like '$query'");
 */

$rows = array();
while ($row = fetch_assoc($rs)) {
    $rows[] = $row;
}

echo json_encode($rows);
?>
