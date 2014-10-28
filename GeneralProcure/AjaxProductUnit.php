<?php
include_once '../lib/DbManager.php';
$product_id = getParam('product_id');

$sql = "SELECT u.UNIT_TYPE_NAME
FROM product As p
INNER JOIN unit_type AS u ON u.UNIT_TYPE_ID = p.unit_type_ID
WHERE p.product_id='$product_id'";
$result = find($sql);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>
