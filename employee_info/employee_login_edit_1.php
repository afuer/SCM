<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employee = new employee();

//$employee_id = 3000;
$employeeId = getParam('employeeId');

$var = json_decode($employee->getDataLogin($employeeId));

$card = json_decode($employee->getDataCardNo($employeeId));

$userLevelList = json_decode($employee->userLevelCombo());

$routeList = json_decode($employee->routeCombo());
?>
<link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">

<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>


<form class="form" id="emLoginEdit" action="#" class="form" method="POST" >

    <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employeeId; ?>" /> 
    <table class="ui-state-default1">
        <tbody>

            <tr>
                <td>User ID/Staff ID: </td>
                <td ><input  type="text" readonly="readonly"  name="USER_NAME" Value="<?php echo $card; ?>" data-options="required:true"  /> </td>
                <td></td>
                <td></td>
                    

            </tr>

            <tr>
                <td width="130" >Password:</td>
                <td width="300" ><input    type="password" name="USER_PASS" class="required"  data-options="required:true" /> </td>
                <td width="130">Confirm Password:</td>
                <td width="300"><input   type="password" name="RE_PASSWORD"  data-options="required:true" /></td>

            </tr>
            <tr>
                <td>User Level:</td>
                <td><?php combobox("USER_LEVEL_ID", $userLevelList, $var->USER_LEVEL_ID, true); ?></td>
                <td>Route:</td>
                <td><?php combobox("ROUTE_ID", $routeList, $var->ROUTE_ID, true); ?></td>
            </tr>



        </tbody>
    </table>
    <a href="#" class="button" onclick="saveLogin();">Update</a>
</form>
