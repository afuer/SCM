<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employee = new employee();

//$employee_id = 3000;
$employeeId = getParam('employeeId');

$var = json_decode($employee->getDataEducation($employeeId));

?>
<link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">
<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>

<form class="form" id="emEducationEdit" action="#" method="POST" >
    <input type="hidden" name="EMPLOYEE_EDUCATION_INFO_ID" value="<?php echo $var->EMPLOYEE_EDUCATION_INFO_ID; ?>" /> 
   <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employeeId; ?>" /> 
   <table class="ui-state-default1">
    <tbody>
        <tr>
            <td width="130" >Qualification Title :</td>
            <td width="300" ><input type="text" name="QUALIFICATION_TITLE" value="<?php echo $var->QUALIFICATION_TITLE; ?>" /></td>
            <td width="130">Major:</td>
            <td width="300"><input type="text" name="MAJOR" value="<?php echo $var->MAJOR; ?>" /></td>
        </tr>
        <tr class="fitem">
            <td>Passing Year:</td>
            <td><input name="PASSING_YEAR" class="easyui-datebox" value="<?php echo $var->PASSING_YEAR; ?>" /></td>
            <td>CGPA/Percentage:</td>
            <td><input type="text" name="CGPA_PERCENTAGE" value="<?php echo $var->CGPA_PERCENTAGE; ?>" /></td>
        </tr>
        <tr>
            <td>Institute Name:</td>
            <td><input type="text" name="INSTITUTE_NAME" value="<?php echo $var->INSTITUTE_NAME; ?>" /></td>
            <td>Status:</td>
            <td><input type="text" name="STATUS" value="<?php echo $var->STATUS; ?>" /></td>
        </tr>
        
        <tr class="fitem">
            <td>Start Date:</td>
            <td><input name="START_DATE" class="easyui-datebox" value="<?php echo $var->START_DATE; ?>" /></td>
            <td>End Date:</td>
            <td><input name="END_DATE" class="easyui-datebox" value="<?php echo $var->END_DATE; ?>" /></td>
        </tr>
        <tr>
            <td>Career Info:</td>
            <td><input type="text" name="CAREER_INFO" value="<?php echo $var->INSTITUTE_NAME; ?>" /></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>

</table>
    <a href="#" class="button" onclick="saveEmployeeoEducationInfo();">Update</a>
</form>
