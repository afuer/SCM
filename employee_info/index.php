<?php
include '../lib/DbManager.php';
include 'employee.php';


$module = getParam('module');
$mode = getParam('mode');

$Personal = $module == 'personal' ? 'true' : 'false';
$Office = $module == 'office' ? 'true' : 'false';
$bank_account = $module == 'bank_account' ? 'true' : 'false';
$address = $module == 'address' ? 'true' : 'false';
$login = $module == 'login' ? 'true' : 'false';



$employeeId = getParam('employeeId');
include '../body/header.php';
?>



<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="include.js"></script>

<input type="hidden" name="employeeId" id="employeeId" value="<?php echo $employeeId; ?>" />

<div class="easyui-layout" style="width:1100px; margin: auto; height:850px;">  
    <div data-options="region:'center'" Title='Employee Information' style="padding: 10px 10px; background-color:white; "> 

        <?php include'employee_header.php'; ?>


        <div id="tab_personal" class="easyui-tabs" data-options="fit:true,border:false,plain:true">  
            <div id="empPersonal" title="Personal"  data-options="selected:<?php echo $Personal; ?>">
                <?php
                if ($mode == 'edit' && $module == 'personal') {
                    include 'employee_personal_edit.php';
                } else {
                    $employee_personal = new employee();
                    $var = $employee_personal->getDataPersonalInfo($employeeId);
                    include 'employee_personal_view.php';
                }
                ?>

            </div> 
            <div id="empOfficeInfo" title="Office" data-options="selected:<?php echo $Office; ?>">

                <?php
                if ($mode == 'edit' && $module == 'office') {

                    include 'employee_office_edit.php';
                } else {
                    $employee_office = new employee();
                    $var = $employee_office->getDataOfficeInfo($employeeId);
                    $EMPLOYEE_ID = $var->LINE_MANAGER_ID;
                    $lineManager = $employee_office->supervisorHeading($EMPLOYEE_ID);
                    $RELIEVER_EMP_ID = $var->RELIEVER_EMP_ID;
                    $reliever = $employee_office->supervisorHeading($RELIEVER_EMP_ID);
                    $var = $employee_office->getDataOfficeInfo($employeeId);
                    include 'employee_office_info_view.php';
                }
                ?>

            </div>             



            <div id="empBankAccount" title="Bank Account" data-options="selected:<?php echo $bank_account; ?>">

                <?php
                if ($mode == 'edit' && $module == 'bank_account') {
                    include 'employee_bank_account_edit.php';
                } else {
                    $employee_bank = new employee();
                    $var = $employee_bank->getDataBankAccountInfo($employeeId);
                    include 'employee_bank_account_view.php';
                }
                ?> 

            </div> 
            <div id="empAddress" title="Address" data-options="selected:<?php echo $address; ?>">
                <?php
                if ($mode == 'edit' && $module == 'address') {
                    include 'employee_address_edit.php';
                } else {
                    $employee_address = new employee();
                    $var = $employee_address->getDataAddress($employeeId);
                    include 'employee_address_view.php';
                }
                ?> 
            </div> 
            <div id="empLogin" title="Login Info" data-options="selected:<?php echo $login; ?>">
                <?php
                if ($mode == 'edit' && $module == 'login') {
                    include 'employee_login_edit.php';
                } else {
                    $employee_login = new employee();
                    $var = $employee_login->getDataBankAccountInfo($employeeId);
                    include 'employee_login_edit_view.php';
                }
                ?> 
            </div>             
        </div>
    </div>  
</div> 




<?php include '../body/footer.php'; ?>