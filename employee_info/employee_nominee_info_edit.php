<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employee = new employee();

//$employee_id = 3000;
$employeeId = getParam('employeeId');

$var = json_decode($employee->getDataNominee($employeeId));
$statusList = array(array(1,'Yes'),array(0,'No'));
$nomineeTypeList = json_decode($employee->nomineeTypeCombo());

?>
<link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">
<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>

<form class="form" id="emNomineeEdit" action="#" method="POST" >
    <input type="hidden" name="EMPLOYEE_NOMINEE_INFO_ID" value="<?php echo $var->EMPLOYEE_NOMINEE_INFO_ID; ?>" /> 
   <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employeeId; ?>" /> 
    <table class="ui-state-default1">
    <tbody>
        <tr>
            <td width="130" >Nominee Name :</td>
            <td width="300" ><input type="text" name="NOMINEE_NAME" value="<?php echo $var->NOMINEE_NAME; ?>" /> </td>
            <td width="130">Family Member:</td>
            <td width="300"><?php combobox("IS_FAMILY_MEMBER", $statusList, $var->IS_FAMILY_MEMBER, true); ?></td>
        </tr>
        <tr>
            <td>Nominee Type:</td>
            <td><?php combobox("NOMINEE_TYPE_ID", $nomineeTypeList, $var->NOMINEE_TYPE_ID, true); ?></td>
            <td>Relation:</td>
            <td><input type="text" name="RELATIONSHIP" value="<?php echo $var->RELATIONSHIP; ?>" /></td>
        </tr>
        <tr class="fitem">
            <td>Date of Birth:</td>
            <td><input name="DATE_OF_BIRTH" class="easyui-datebox" value="<?php echo $var->DATE_OF_BIRTH; ?>" /></td>
            <td>Percentage:</td>
            <td><input type="text" name="NOMINEE_PERCENTAGE" value="<?php echo $var->NOMINEE_PERCENTAGE; ?>" /></td>
        </tr>

        
    </tbody>
    </table>
    <a href="#" class="button" onclick="saveEmployeeoNomineeInfo();">Update</a>
</form>
