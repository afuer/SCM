<?php
include '../lib/DbManager.php';
$card_no = getParam('card_no');

$sql = "SELECT CONCAT(ed.FIRST_NAME,' ',ed.LAST_NAME, '->', ed.CARD_NO) AS empMame, DESIGNATION_NAME, ed.DESIGNATION_ID
FROM employee AS ed 
LEFT JOIN designation AS d ON d.DESIGNATION_ID=ed.DESIGNATION_ID
WHERE CARD_NO='$card_no'";
$result = find($sql);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>
