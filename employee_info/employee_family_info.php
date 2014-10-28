<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employee_personal = new employee();
$employeeId = getParam('employeeId');

$var = json_decode($employee_personal->getDataFamilyInfo($employeeId));
?>


<table class="ui-state-default1">
    <tbody>
        <tr>
            <td width="130" > Name :</td>
            <td width="300" > <?php echo $var->FAMILY_MEMBER_NAME; ?></td>
            <td width="130">Relation:</td>
            <td width="300"><?php echo $var->FAMILY_RELATIONSHIP_TYPE; ?></td>
        </tr>
        <tr>
            <td>Is CBL Employee:</td>
            <td><?php echo $var->STATUS; ?></td>
            <td>Email:</td>
            <td><?php echo $var->EMAIL; ?></td>
        </tr>
        <tr>
            <td>Contact No:</td>
            <td><?php echo $var->CONTACT_PHONE_NO; ?></td>
            <td>Profession:</td>
            <td><?php echo $var->PROFESSION; ?></td>
        </tr>
        <tr>
            <td>Date Of Birth:</td>
            <td><?php echo bdDate($var->DATE_OF_BIRTH) ; ?></td>
            <td></td>
            <td></td>
        </tr>
        
    </tbody>

</table>
<a class="button" id="employeePersonal" href="#" onclick="editEmployeeFamilyInfo()"  > Modify</a>
