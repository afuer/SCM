<?php
include '../lib/DbManager.php';
include ('employee.php');

$val = $_REQUEST['val'];
$employee = new employee();
$lineManager = $employee->GetDataLinemanager($val);
?>
<td colspan="3">
    Line Manager Name:&nbsp;&nbsp; &nbsp;
        <?php 
        if($lineManager->CARD_NO !=''){
        echo $lineManager->CARD_NO . ' ' . $lineManager->FIRST_NAME . ' ' . $lineManager->MIDDLE_NAME . ' ' . $lineManager->LAST_NAME . ' ' . $lineManager->DESIGNATION_NAME; 
        } else { echo 'Not Found';}
        ?>
</td>
<td></td>



