<?php
include_once '../lib/DbManager.php';
include_once 'employee.php';
$employeeAddress = new employee();

//$employee_id = 3000;
$employeeId = getParam('employeeId');

$var = $employeeAddress->getDataAddress($employeeId);

//$accountTypeList = json_decode($employeeOffice->accountTypeCombo());
$thanaList = json_decode($employeeAddress->thanaCombo());
?>

<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>

<form class="form" id="emBankAddressEdit" action="#" method="POST" >
    <input type="hidden" name="EMPLOYEE_ADDRESS_ID" value="<?php echo $var->EMPLOYEE_ADDRESS_ID; ?>" /> 
    <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employeeId; ?>" /> 

    <table class="table">
        <tr>
            <td width="130" ><b>Present Address</b></td>
            <td width="280"></td>
            <td width="150"><b>Permanent Address</b></td>
            <td width="300" ></td>
        </tr>
        <tr>
            <td>Address1:</td>
            <td  ><textarea name="PRESENT_ADDRESS1" > <?php echo $var->PRESENT_ADDRESS1; ?></textarea></td>
            <td >Address1:</td>
            <td ><textarea name="PERMANENT_ADDRESS1"> <?php echo $var->PERMANENT_ADDRESS1; ?></textarea></td>
        </tr>
        <tr>
            <td  >Address2:</td>
            <td  ><input type="text" name="PRESENT_ADDRESS2" value="<?php echo $var->PRESENT_ADDRESS2; ?>" /> </td>
            <td >Address2:</td>
            <td><input type="text" name="PERMANENT_ADDRESS2" value="<?php echo $var->PERMANENT_ADDRESS2; ?>" /> </td>
        </tr>
        <tr>
            <td >Thana:</td>
            <td > <?php combobox("PRESENT_THANA_ID", $thanaList, $var->PRESENT_THANA_ID, true); ?></td>
            <td >Thana:</td>
            <td ><?php combobox("PERMANENT_THANA_ID", $thanaList, $var->PERMANENT_THANA_ID, true); ?></td>
        </tr>
        <tr>
            <td >Postal Code:</td>
            <td ><input type="text" name="PRESENT_POSTAL_CODE" value="<?php echo $var->PRESENT_POSTAL_CODE; ?>" /> </td>
            <td >Postal Code:</td>
            <td ><input type="text" name="PERMANENT_POSTAL_CODE" value="<?php echo $var->PERMANENT_POSTAL_CODE; ?>" /></td>
        </tr>
    </table>
    <a href="#" class="button" onclick="saveEmployeeoAddress();">Update</a>
</form>
