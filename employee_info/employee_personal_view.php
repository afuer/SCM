<table class = "table">
        <tr>
            <td width = "130">First Name:</td>
            <td width = "300"> <?php echo $var->FIRST_NAME; ?></td>
            <td width="130">Middle Name:</td>
            <td width="300"><?php echo $var->MIDDLE_NAME; ?></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><?php echo $var->LAST_NAME; ?></td>
            <td>Full Name:</td>
            <td><?php echo $var->FIRST_NAME . ' ' . $var->MIDDLE_NAME . ' ' . $var->LAST_NAME; ?></td>
        </tr>
        <tr>
            <td>Marital Status:</td>
            <td><?php echo $var->MARITAL_STATUS_NAME; ?></td>
            <td>Gander:</td>
            <td><?php echo $var->GANDER_NAME; ?></td>
        </tr>
        <tr>
            <td>Nationality:</td>
            <td><?php echo $var->COUNTRY_NAME; ?></td>
            <td>Date Of Birth:</td>
            <td><?php echo bddate($var->DATE_OF_BIRTH); ?></td>
        </tr>
        <tr>
            <td>Religion:</td>
            <td><?php echo $var->RELIGION_NAME; ?></td>
            <td>National Id:</td>
            <td><?php echo $var->NATIONAL_ID; ?></td>
        </tr>
        <tr>
            <td>Passport No:</td>
            <td><?php echo $var->PASSPORT_NO; ?></td>
            <td>Passport Issue Date:</td>
            <td><?php echo bdDate($var->PASSPORT_ISSUE_DATE); ?></td>
        </tr>
        <tr>
            <td>Passport Expair Date:</td>
            <td><?php echo bddate($var->PASSPORT_EXPIRE_DATE); ?></td>
            <td>Cell No:</td>
            <td><?php echo $var->CELL_NO; ?></td>
        </tr>
        <tr>
            <td>Emergency phone No:</td>
            <td><?php echo $var->EMERGENCY_PHONE_NO; ?></td>
            <td>Home phone No:</td>
            <td><?php echo $var->HOME_PHONE_NO; ?></td>
        </tr>
        <tr>
            <td>Personal Email:</td>
            <td><?php echo $var->PERSONAL_EMAIL; ?></td>
            <td>PABX Number:</td>
            <td><?php echo $var->PABAX_NO; ?></td>
        </tr>
        <tr>
            <td>PABX Ext:</td>
            <td><?php echo $var->PABX_EXT; ?></td>
            <td>Reference Information:</td>
            <td><?php echo $var->REFERENCE_INFO; ?></td>
        </tr>
</table>
<a class="button" id="employeePersonal" href="?module=personal&mode=edit&employeeId=<?php echo $employeeId; ?>">Modify</a>
