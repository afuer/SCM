<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employeeBankAccount = new employee();
$employeeId = getParam('employeeId');

$var = $employeeBankAccount->getDataBankAccountInfo($employeeId);

?>


<table class="ui-state-default">
    <tbody>
        <tr>
            <td width="130" >Account Number:</td>
            <td width="300" > <?php echo $var->ACCOUNT_NUMBER; ?></td>
            <td width="130">Account Type:</td>
            <td width="300"><?php echo $var->ACCOUNT_TYPE_NAME; ?></td>
        </tr>
        <tr>
            <td>Branch:</td>
            <td><?php echo $var->BRANCH_DEPT_NAME; ?></td>
            <td></td>
            <td></td>
        </tr>
        
    </tbody>

</table>
<a class="button" id="employeePersonal" href="?module=bank_account&mode=edit&employeeId=<?php echo $employeeId; ?>">Modify</a>
