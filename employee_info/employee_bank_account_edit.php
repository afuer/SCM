<?php
$employeeOffice = new employee();

$employeeId = getParam('employeeId');

$var = $employeeOffice->getDataBankAccountInfo($employeeId);
$accountTypeList = json_decode($employeeOffice->accountTypeCombo());
$branchList = json_decode($employeeOffice->branchCombo());
?>
<link rel="stylesheet" type="text/css" href="../public/themes/default/easyui.css">
<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>

<form class="form" id="emBankAccountEdit" action="#" method="POST" >
    <input type="hidden" name="EMPLOYEE_BANK_ACCOUNT_INFO_ID" value="<?php echo $var->EMPLOYEE_BANK_ACCOUNT_INFO_ID; ?>" /> 
    <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employeeId; ?>" /> 
    <table class="table">
        <tr>
            <td width="130" >Account Number:</td>
            <td width="300" > <input type="text" name="ACCOUNT_NUMBER" value="<?php echo $var->ACCOUNT_NUMBER; ?>" /></td>
            <td width="130">Account Type:</td>
            <td width="300"><?php combobox("ACCOUNT_TYPE_ID", $accountTypeList, $var->ACCOUNT_TYPE_ID, true); ?></td>
        </tr>
        <tr>
            <td width="130" >Branch:</td>
            <td width="300" ><?php combobox("BRANCH_ID", $branchList, $var->BRANCH_ID, true); ?></td>
            <td width="130"></td>
            <td width="300"></td>
        </tr>
    </table>
    <a href="#" class="button" onclick="saveEmployeeoBankAccount();">Update</a>
</form>
