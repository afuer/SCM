
<table class="table">
    <tr>
        <td width="130" >Employee Type:</td>
        <td width="300" > <?php echo $var->EMPLOYEE_TYPE_NAME; ?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Line Manager :</td>
        <td><?php echo $lineManager->CARD_NO . ' ' . $lineManager->FIRST_NAME . ' ' . $lineManager->MIDDLE_NAME . ' ' . $lineManager->LAST_NAME . ' ' . $lineManager->DESIGNATION_NAME; ?></td>
        <td>Job:</td>
        <td><?php echo $var->JOB; ?></td>
    </tr>
    <tr>
        <td>Is Reliever  :</td>
        <td>
            <input type="checkbox" id="IS_RELIEVER" name="IS_RELIEVER" <?php
            if ($var->IS_RELIEVER == '1') {
                echo 'checked';
            }
            ?> value="1">
        </td>
        <td></td>
        <td></td>

    </tr>
    <tr>
        <td>Reliever:</td>
        <td colspan="3"><?php echo $reliever->CARD_NO . ' ' . $reliever->FIRST_NAME . ' ' . $reliever->MIDDLE_NAME . ' ' . $reliever->LAST_NAME . ' ' . $reliever->DESIGNATION_NAME; ?></td>
    </tr>
    <tr>
        <td>Grade:</td>
        <td><?php echo $var->GRADE_NAME; ?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>Office Phone No:</td>
        <td><?php echo $var->OFFICE_PHONE_NO; ?></td>
        <td>Joining Date:</td>
        <td><?php echo bddate($var->JOINING_DATE); ?></td>
    </tr>
    <tr>
        <td>Assignment Category:</td>
        <td><?php echo $var->ASSIGNMENT_CATEGORY_NAME; ?></td>
        <td>Office Email:</td>
        <td><?php echo $var->OFFICE_EMAIL; ?></td>
    </tr>
    <tr>
        <td>Handicap Info:</td>
        <td><?php echo $var->HANDICAP_INFO; ?></td>
        <td>Retirement Date:</td>
        <td><?php echo bddate($var->RETIREMENT_DATE); ?></td>
    </tr>

    <tr>
        <td>Working Location:</td>
        <td><?php echo $var->LOCATION; ?></td>
        <td>Mobile Bill:</td>
        <td><?php echo $var->MOBILE_BILL; ?></td>
    </tr>
    <tr>
        <td>Internet Bill:</td>
        <td><?php echo $var->INTERNET_BILL; ?></td>
        <td>Others Bill:</td>
        <td><?php echo $var->OTHERS_BILL; ?></td>
    </tr>

    <?php
    if ($var->EMPLOYEE_TYPE_ID == '0' OR $var->EMPLOYEE_TYPE_ID == '2') {
        ?>

        <tr>
            <td >Supplier:</td>
            <td ><?php echo $var->SUPPLIER_NAME; ?></td>
            <td>Salary:</td>
            <td ><?php echo $var->SALARY; ?></td>
        </tr>
        <?php
    }
    ?>
</table>

<a class="button" id="employeeOffice" href="?module=office&mode=edit&employeeId=<?php echo $employeeId; ?>">Modify</a>
