
<table class="table">
        <tr>
            <td width="130"><b>Present Address</b></td>
            <td width="300"></td>
            <td width="130"><b>Permanent Address</b></td>
            <td width="300" ></td>
        </tr>
        <tr>
            <td width="130" >Address1:</td>
            <td width="300" > <?php echo $var->PRESENT_ADDRESS1; ?></td>
            <td width="130">Address1:</td>
            <td width="300"><?php echo $var->PERMANENT_ADDRESS1; ?></td>
        </tr>
        <tr>
            <td width="130" >Address2:</td>
            <td width="300" > <?php echo $var->PRESENT_ADDRESS2; ?></td>
            <td width="130">Address2:</td>
            <td width="300"><?php echo $var->PERMANENT_ADDRESS2; ?></td>
        </tr>
        <tr>
            <td width="130" >Thana:</td>
            <td width="300" > <?php echo $var->PRESENT_THANA; ?></td>
            <td width="130">Thana:</td>
            <td width="300"><?php echo $var->PERMANENT_THANA; ?></td>
        </tr>
        <tr>
            <td width="130" >Postal Code:</td>
            <td width="300" > <?php echo $var->PRESENT_POSTAL_CODE; ?></td>
            <td width="130">Postal Code:</td>
            <td width="300"><?php echo $var->PERMANENT_POSTAL_CODE; ?></td>
        </tr>
</table>

<a class="button" id="employeePersonal" href="?module=address&mode=edit&employeeId=<?php echo $employeeId; ?>">Modify</a>
