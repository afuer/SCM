<?php
include_once '../lib/DbManager.php';
include_once 'employee.php';
$employeeOffice = new employee();

$employeeId = getParam('employeeId');
//$employee_id = 3000;

$var = $employeeOffice->getDataOfficeInfo($employeeId);

$EMPLOYEE_ID = $var->LINE_MANAGER_ID;
$lineManager = $employeeOffice->supervisorHeading($EMPLOYEE_ID);
$RELIEVER_EMP_ID = $var->RELIEVER_EMP_ID;
$reliever = $employeeOffice->supervisorHeading($RELIEVER_EMP_ID);

$employeeTypeList = json_decode($employeeOffice->employeeTypeCombo());
$suplierList = json_decode($employeeOffice->supplierCombo());
$employeeList = json_decode($employeeOffice->employeeCombo());
$gradeList = json_decode($employeeOffice->gradeCombo());
$officeTypeList = json_decode($employeeOffice->officeTypeCombo());
$assignmentCategoryList = json_decode($employeeOffice->assignmentCategoryCombo());
?>
<link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">
<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>


<form class="form" id="emOfficeInfoEdit" action="#" method="POST" >
    <div id="loder" class="datagrid-mask-msg" style="display:none; left: 470.5px;">Processing, please wait ...</div>
    <input type="hidden" name="EMPLOYEE_OFFICE_INFO_ID" value="<?php echo $var->EMPLOYEE_OFFICE_INFO_ID; ?>" /> 
    <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employeeId; ?>" /> 
    <table class="table">
            <tr>
                <td width="130" >Employee Type:</td>
                <td width="300" ><?php combobox("EMPLOYEE_TYPE_ID", $employeeTypeList, $var->EMPLOYEE_TYPE_ID, true); ?></td>
                <td width="130"></td>
                <td width="300"></td>
            </tr>
            <tr>
                <td>Line Manager:</td>
                <td>
                    <input type="text" name="LINE_MANAGER_ID" class="easyui-validatebox" data-options="required:true" id="SUPERVISOR_ID" 
                           value="<?php echo $lineManager->CARD_NO; ?>" > 
                </td>
                <td>Job:</td>
                <td><input type="text" name="JOB" value="<?php echo $var->JOB; ?>" /></td>
            </tr>
            <tr id="AjaxLineManager">
                <td>Line Manager:</td>
                <td colspan="3"><?php echo $lineManager->CARD_NO . ' ' . $lineManager->FIRST_NAME . ' ' . $lineManager->MIDDLE_NAME . ' ' . $lineManager->LAST_NAME . ' ' . $lineManager->DESIGNATION_NAME; ?></td>
            </tr>
            <tr>
                <td>Is Reliever  :</td>
                <td><input type="checkbox" id="IS_RELIEVER" name="IS_RELIEVER" <?php if($var->IS_RELIEVER =='1'){echo 'checked';} ?> value="1"></td>
                <td></td>
                <td></td>

            </tr>
            <?php $display = ($var->IS_RELIEVER == '' ? 'style="display:none;"' : '');?>
            
            <tr id="Reliever" <?php echo $display; ?> >
                <td>Reliever Emp Id:</td>
                <td>
                    <input type="text" name="RELIEVER_EMP_ID" id="RELIEVER_EMP_ID" class="easyui-validatebox"  id="RELIEVER_EMP_ID" 
                    value="<?php echo $reliever->CARD_NO; ?>" > 
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr id="AjaxReliever">
                <td>Reliever:</td>
                <td colspan="3"><?php echo $reliever->CARD_NO . ' ' . $reliever->FIRST_NAME . ' ' . $reliever->MIDDLE_NAME . ' ' . $reliever->LAST_NAME . ' ' . $reliever->DESIGNATION_NAME; ?></td>
            </tr>
            <tr>
                <td>Grade:</td>
                <td><?php combobox("GRADE_ID", $gradeList, $var->GRADE_ID, true); ?></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td>Office Phone No:</td>
                <td><input type="text" name="OFFICE_PHONE_NO" value="<?php echo $var->OFFICE_PHONE_NO; ?>" /></td>
                <td>Joining Date:</td>
                <td><input name="JOINING_DATE" class="easyui-datebox" value="<?php echo $var->JOINING_DATE; ?>" data-options="formatter:myformatter,parser:myparser" /></td>
            </tr>
            <tr>
                <td>Assignment Category:</td>
                <td> <?php combobox("ASSIGNMENT_CATEGORY_ID", $assignmentCategoryList, $var->ASSIGNMENT_CATEGORY_ID, true); ?></td>
                <td>Office Email:</td>
                <td><input type="text" name="OFFICE_EMAIL" value="<?php echo $var->OFFICE_EMAIL; ?>" /></td>
            </tr>

            <tr>
                <td>Handicap Info:</td>
                <td><input type="text" name="HANDICAP_INFO" value="<?php echo $var->HANDICAP_INFO; ?>" /></td>
                <td>Retirement Date:</td>
                <td> <input name="RETIREMENT_DATE" class="easyui-datebox" value="<?php echo $var->RETIREMENT_DATE; ?>" /></td>
            </tr>
            <tr>
                <td>Working Location:</td>
                <td><input type="text" name="LOCATION" value="<?php echo $var->LOCATION; ?>" /></td>
                <td>Mobile Bill:</td>
                <td><input type="text" name="MOBILE_BILL" value="<?php echo $var->MOBILE_BILL; ?>" /></td>
            </tr>
            <tr>
                <td>Internet Bill:</td>
                <td><input type="text" name="INTERNET_BILL" value="<?php echo $var->INTERNET_BILL; ?>" /></td>
                <td>Others Bill:</td>
                <td><input type="text" name="OTHERS_BILL" value="<?php echo $var->OTHERS_BILL; ?>" /></td>
            </tr>

            <tr id="outerEmployee">
                <?php if ($var->EMPLOYEE_TYPE_ID == '0' OR $var->EMPLOYEE_TYPE_ID == '2') { ?>
                    <td>Supplier:</td>
                    <td><?php combobox("SUPPLIER_ID", $suplierList, $var->SUPPLIER_ID, true) ?></td>
                    <td>Salary:</td>
                    <td><input type="text" name="SALARY" value="<?php echo $var->SALARY; ?>"/></td>
                <?php }
                ?>
            </tr>
    </table>
    <a href="#" class="button" onclick="saveEmployeeoffice();">Update</a>
</form>
