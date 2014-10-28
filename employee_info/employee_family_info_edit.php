<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employeeOffice = new employee();

//$employee_id = 3000;
$employeeId = getParam('employeeId');

$var = json_decode($employeeOffice->getDataFamilyInfo($employeeId));

?>
<link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">
<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>

<form class="form" id="emFamilyInfoEdit" action="#" method="POST" >
    <input type="hidden" name="EMPLOYEE_FAMILY_INFO_ID" value="<?php echo $var->EMPLOYEE_FAMILY_INFO_ID; ?>" /> 
   <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employeeId; ?>" /> 
    <table class="ui-state-default1">
        <tbody>
        <tr>
            <td width="130" > Name :</td>
            <td width="300" > <input type="text" name="FAMILY_MEMBER_NAME" value="<?php echo $var->FAMILY_MEMBER_NAME; ?>"/> </td>
            <td width="130">Relation:</td>
            <td width="300"><input type="text" name="FAMILY_RELATIONSHIP_TYPE" value="<?php echo $var->FAMILY_RELATIONSHIP_TYPE; ?>"/></td>
        </tr>
        <tr>
            <td>Is CBL Employee:</td>
            <td><input type="text" name="IS_CBL_EMPLOYEE" value="<?php echo $var->IS_CBL_EMPLOYEE; ?>"/></td>
            <td>Email:</td>
            <td><input type="text" name="EMAIL" value="<?php echo $var->EMAIL; ?>"/></td>
        </tr>
        <tr>
            <td>Contact No:</td>
            <td><input type="text" name="CONTACT_PHONE_NO" value="<?php echo $var->CONTACT_PHONE_NO; ?>"/> </td>
            <td>Profession:</td>
            <td> <input type="text" name="PROFESSION" value="<?php echo $var->PROFESSION; ?>"/></td>
        </tr>
        <tr class="fitem">
            <td>Date Of Birth:</td>
            <td><input name="DATE_OF_BIRTH" class="easyui-datebox" value="<?php echo $var->DATE_OF_BIRTH; ?>" /> </td>
            <td></td>
            <td></td>
        </tr>




        </tbody>
    </table>
    <a href="#" class="button" onclick="saveEmployeeoFamilyInfo();">Update</a>
</form>
