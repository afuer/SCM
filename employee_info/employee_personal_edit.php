<?php
$employee_personal = new employee();
$var = $employee_personal->getDataPersonalInfo($employeeId);
$maritalStatusList = json_decode($employee_personal->maritalStatusCombo());
$ganderList = json_decode($employee_personal->ganderCombo());
$countryList = json_decode($employee_personal->countryCombo());
$religionList = json_decode($employee_personal->religionCombo());
?>

<form class="form" id="emPerInfoEdit" action="#" method="POST" >
    <input type="hidden" name="EMPLOYEE_ID" value="<?php echo $employee_id; ?>" /> 
    <table class="table">
            <tr>
                <td width="130">First Name:</td>
                <td width="300" ><input type="text" name="FIRST_NAME" value="<?php echo $var->FIRST_NAME; ?>" /></td>
                <td width="130">Middle Name:</td>
                <td width="300"><input type="text" name="MIDDLE_NAME" value="<?php echo $var->MIDDLE_NAME; ?>" /></td>
            </tr>
            <tr>
                <td>Last Name:</td>
                <td> <input type="text" name="LAST_NAME" value="<?php echo $var->LAST_NAME; ?>" /></td>
                <td>Full Name:</td>
                <td><?php echo $var->FIRST_NAME . ' ' . $var->MIDDLE_NAME . ' ' . $var->LAST_NAME; ?> </td>
            </tr>
            <tr>
                <td>Marital Status:</td>
                <td><?php combobox("MARITAL_STATUS_ID", $maritalStatusList, $var->MARITAL_STATUS_ID, true); ?></td>

                <td>Gander:</td>
                <td><?php combobox("GANDER_ID", $ganderList, $var->GANDER_ID, true); ?></td>
            </tr>
            <tr class="fitem">
                <td>Nationality:</td>
                <td> <?php combobox("NATIONALITY_ID", $countryList, $var->NATIONALITY_ID, true); ?></td>
                <td>Date Of Birth:</td>
                <td><input name="DATE_OF_BIRTH" class="easyui-datebox" value="<?php echo $var->DATE_OF_BIRTH; ?>" data-options="formatter:myformatter,parser:myparser" /></td>
            </tr>
            <tr>
                <td>Religion:</td>
                <td><?php combobox("RELIGION_ID", $religionList, $var->RELIGION_ID, true); ?></td>
                <td>National Id:</td>
                <td> <input type="text" name="NATIONAL_ID" value="<?php echo $var->NATIONAL_ID; ?>" /></td>
            </tr>
            <tr>
                <td>Passport No:</td>
                <td><input type="text" name="PASSPORT_NO" value="<?php echo $var->PASSPORT_NO; ?>" /> </td>
                <td>Passport Issue Date:</td>
                <td> <input name="PASSPORT_ISSUE_DATE" class="easyui-datebox" value="<?php echo $var->PASSPORT_ISSUE_DATE; ?>" data-options="formatter:myformatter,parser:myparser" /> </td>
            </tr>
            <tr>
                <td>Passport Expire Date:</td>
                <td><input name="PASSPORT_EXPIRE_DATE" class="easyui-datebox" value="<?php echo $var->PASSPORT_EXPIRE_DATE; ?>" data-options="formatter:myformatter,parser:myparser" /></td>
                <td>Cell No:</td>
                <td> <input type="text" name="SELL_NO" value="<?php echo $var->CELL_NO; ?>" /></td>
            </tr>
            <tr>
                <td>Emergency phone No:</td>
                <td> <input type="text" name="EMERGENCY_PHONE_NO" value="<?php echo $var->EMERGENCY_PHONE_NO; ?>" /></td>
                <td>Home phone No:</td>
                <td><input type="text" name="HOME_PHONE_NO" value="<?php echo $var->HOME_PHONE_NO; ?>" /></td>
            </tr>
            <tr>
                <td>Personal Email:</td>
                <td><input name="PERSONAL_EMAIL"  class="easyui-validatebox" data-options="required:true,validType:'email'" value="<?php echo $var->PERSONAL_EMAIL; ?>" /></td>
                <td>PABX Number:</td>
                <td> <input type="text" name="PABAX_NO" value="<?php echo $var->PABAX_NO; ?>" /></td>
            </tr>
            <tr>
                <td>PABX Ext:</td>
                <td><input type="text" name="PABX_EXT" value="<?php echo $var->PABX_EXT; ?>" /></td>
                <td>Reference Information:</td>
                <td> <input type="text" name="REFERENCE_INFO" value="<?php echo $var->REFERENCE_INFO; ?>" /></td>
            </tr>

    </table>
    <a href="#" class="button" onclick="saveEmployee();">Update</a>
</form>
