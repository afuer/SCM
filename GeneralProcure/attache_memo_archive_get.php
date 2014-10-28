<?php

include_once '../lib/DbManager.php';


$sql = "SELECT FILE_ATTACH_LIST_ID, ATTACH_TITTLE, ATTACH_FILE_PATH from memo_file_attach_list ORDER BY ATTACH_TITTLE";
$result = $db->query($sql);

$items = array();
while ($row = mysql_fetch_object($result)) {
    array_push($items, $row);
}
echo json_encode($items);
?>