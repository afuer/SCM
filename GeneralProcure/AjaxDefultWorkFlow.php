<?php
include '../lib/DbManager.php';
$val = getParam('val');

$sql = "SELECT ed.FULL_NAME, d.DESIGNATION_NAME, di.DIVISION_NAME, EMPLOYEE_ID,
ed.DESIGNATION_ID, gd.WORKFLOW_PROCESS_TYPE_ID, 
wpt.WORKFLOW_PROCESS_NAME, ed.CARD_NO, dep.BRANCH_DEPT_NAME

FROM workflow_group_details As gd
INNER JOIN employee As ed On ed.DESIGNATION_ID = gd.DESIGNATION_ID
INNER JOIN designation AS d ON d.DESIGNATION_ID = ed.DESIGNATION_ID
LEFT JOIN division AS di ON di.DIVISION_ID=ed.DIVISION_ID
LEFT JOIN branch_dept AS dep ON dep.BRANCH_DEPT_ID=ed.BRANCH_DEPT_ID
LEFT JOIN workflow_process_type AS wpt ON wpt.WORKFLOW_PROCESS_ID=gd.WORKFLOW_PROCESS_TYPE_ID
WHERE  WORKFLOW_GROUP_ID ='$val' AND ed.ISAPPROVAL='Yes'";
$result = query($sql);
?>
<table class="ui-state-default" id="WorkflowTab_defult" style="width: 780px;">
    <thead>
    <th width="30">S/N</th>
    <th>Work Flow Process</th>
    <th width="200">Dep/Branch</th>
    <th width="150">Designation</th>
    <th width="150">Division</th>
</thead>
<tbody>
    <?php
    $i = 1;
    while ($DefultRows = mysql_fetch_object($result)) {
        ?>
        <tr>
            <td><?php echo $i; ?>.</td>
            <td>
                <?php echo $DefultRows->WORKFLOW_PROCESS_NAME; ?> 
                <input type="hidden" name="workflow_process[]" value="<?php echo $DefultRows->WORKFLOW_PROCESS_TYPE_ID; ?>"/>
            </td>
            <td><input type="hidden" name="EmployeeId[]" id="cardno" class="cardno" value="<?php echo $DefultRows->CARDNO; ?>"/> <?php //echo $DefultRows->FULL_NAME;  ?> </td>
            <td>
                <?php echo $DefultRows->DESIGNATION_NAME; ?>
                <input type="hidden" name="EmployeeDesignID[]" value="<?php echo $DefultRows->DESIGNATION_ID; ?>"/>
            </td>
            <td><?php echo $DefultRows->DIVISION_NAME; ?></td>

        </tr>
        <?php
        $i++;
    }
    ?>
</tbody>

</table>