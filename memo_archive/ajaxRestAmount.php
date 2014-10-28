<?php
include_once '../lib/DbManager.php';
$searchID= $_REQUEST ['id'];
$db = new DbManager();
$db->OpenDb();

$totGivenAmount = findValue("SELECT APPROVED_AMOUNT FROM memo_archive WHERE MEMO_ARCHIVE_ID='$searchID'");
$totPaidAmount = findValue("SELECT SUM(amount) FROM memo_archive_details WHERE memo_archive_id='$searchID' GROUP BY memo_archive_id");
$db->CloseDb();
$restAmount = $totGivenAmount-$totPaidAmount;

echo $restAmount;
?>
