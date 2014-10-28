<?php

include '../lib/DbManager.php';

$query = getParam('q');

$query = $query == '' ? '%' : "%$query%";
//$q = isset($_POST['q']) ? strval($_POST['q']) : '';

//$db = new DbManager();
//$db->OpenDb();

$rs = query("SELECT MEMO_ARCHIVE_ID, MEMO_REF, MEMO_SUBJECT, MEMO_DATE, MEMO_TYPE, APPROVED_AMOUNT FROM memo_archive WHERE MEMO_REF like '$query'");
$rows = array();
while ($row = fetch_assoc($rs)) {
    $rows[] = $row;
}

//$db->CloseDb();
echo json_encode($rows);
?>
