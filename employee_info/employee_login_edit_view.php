<?php
$employee = new employee();

//$employee_id = 3000;
$employeeId = getParam('employeeId');

$var = $employee->getDataLogin($employeeId);

$card = json_decode($employee->getDataCardNo($employeeId));

$userLevelList = json_decode($employee->userLevelCombo());

$routeList = json_decode($employee->routeCombo());
$processDeptId = $db->rs2array("SELECT PROCESS_DEPT_ID, PROCESS_DEPT_NAME FROM process_dept");
?>
<link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">

</br>

<?php
if ($var->EMPLOYEE_ID > '0') {
    echo '<h2>Create New Password</h2>';
}
?>


<form class="form" id="emLoginEdit" action="#" class="form" method="POST" >
    <input type="hidden" name="GET_USER_PASS" value="<?php echo $var->USER_PASS; ?>" />
    <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employeeId; ?>" /> 

    <table class="table">
        <tr>
            <td>Card No: </td>
            <td > <input disabled type="text" Value="<?php echo $card; ?>" />
                <input  type="hidden"  name="USER_NAME" Value="<?php echo $card; ?>" data-options="required:true"  /> 
            </td>
            <td></td>
            <td></td>


        </tr>

        <tr>
            <td width="150" >Password:</td>
            <td width="200" ><input style="width:80%;" type="password" name="USER_PASS" id="USER_PASS" class="required"  data-options="required:true" /> </td>
            <td width="150">Re Password:</td>
            <td width="200"><input style="width:80%;"  type="password" name="RE_PASSWORD" id="RE_PASSWORD"  data-options="required:true" /></td>

        </tr>
        <tr>
            <td>User Level:</td>
            <td><?php combobox("USER_LEVEL_ID", $userLevelList, $var->USER_LEVEL_ID, true, '', 'ajax_process_dept'); ?></td>

            <td id="ajax_process_dept" colspan="2"><?php combobox("ROUTE_ID", $processDeptId, $var->ROUTE_ID, TRUE); ?></td>
        </tr>
    </table>
    <a href="#" class="button" onclick="saveLogin();">Update</a>
</form>
