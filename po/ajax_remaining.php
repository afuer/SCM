<?php
include '../lib/DbManager.php';

$val = getParam('val');
$wo_amount = getParam('wo_amount');
$paid_amount = getParam('paid_amount');
$remain_amount = $wo_amount - ($paid_amount + $val);
?>
<input type="text" name="remain_amount" value="<?php echo round($remain_amount, 2); ?>" readonly="readonly" />