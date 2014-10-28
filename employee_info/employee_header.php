<?php

$employeeId = getParam('employeeId');
$employeeHeader = new employee();
$var_head = $employeeHeader->getEmployeeHeading($employeeId);
$EMPLOYEE_ID = $var_head->LINE_MANAGER_ID;
$lineManager = $employeeHeader->supervisorHeading($EMPLOYEE_ID);
// echo "<script>location.replace('employee_header.php');</script>";
?>


<table class="ui-state-default">
    <tr>
        <td>Employee Name:</td>
        <td width="300"><?php echo $var_head->FIRST_NAME . ' ' . $var_head->MIDDLE_NAME . ' ' . $var_head->LAST_NAME; ?></td>
        <td>Card No:</td>
        <td><?php echo $var_head->CARD_NO; ?></td>
    </tr>
    <tr>
        <td>Designation:</td>
        <td><?php echo $var_head->DESIGNATION_NAME; ?></td>
        <td>Grade:</td>
        <td><?php echo $var_head->GRADE_NAME; ?></td>
    </tr>
    <tr>
        <td id="lineManager">Line Manager: </td>
        <td><?php echo $lineManager->CARD_NO . ' ' . $lineManager->FIRST_NAME . ' ' . $lineManager->MIDDLE_NAME . ' ' . $lineManager->LAST_NAME . ' ' . $lineManager->DESIGNATION_NAME; ?></td>
        <?php if ($var->SUPPLIER_ID != '') { ?>
            <td >Supplier:</td>
            <td id="supplierHeading"><?php echo $var_head->SUPPLIER_NAME; ?></td>
            <?php
        } else {
            echo '<td></td><td></td>';
        }
        ?>
    </tr>
</table>
<br/>


