<?php
include '../lib/DbManager.php';
include ('employee.php');

$val = $_REQUEST['val'];
$employee = new employee();
$lineManager = $employee->GetDataLinemanager($val);
?>
<td colspan="3">Reliever Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;<?php echo $lineManager->CARD_NO . ' ' . $lineManager->FIRST_NAME . ' ' . $lineManager->MIDDLE_NAME . ' ' . $lineManager->LAST_NAME . ' ' . $lineManager->DESIGNATION_NAME; ?></td>
<td></td>



