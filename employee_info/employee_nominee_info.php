<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employee_personal = new employee();
$employeeId = getParam('employeeId');

$var = json_decode($employee_personal->getDataNominee($employeeId));
?>


<table class="ui-state-default1">
    <tbody>
        <tr>
            <td width="130" >Nominee Name :</td>
            <td width="300" > <?php echo $var->NOMINEE_NAME; ?></td>
            <td width="130">Family Member:</td>
            <td width="300"><?php echo $var->STATUS; ?></td>
        </tr>
        <tr>
            <td>Nominee Type:</td>
            <td><?php echo $var->NOMINEE_TYPE_NAME; ?></td>
            <td>Relation:</td>
            <td><?php echo $var->RELATIONSHIP; ?></td>
        </tr>
        <tr>
            <td>Date of Birth:</td>
            <td><?php echo bdDate($var->DATE_OF_BIRTH); ?></td>
            <td>Percentage:</td>
            <td><?php echo $var->NOMINEE_PERCENTAGE; ?></td>
        </tr>

        
    </tbody>

</table>
<a class="button" href="#" onclick="editEmployeeNomineeInfo()"  > Modify</a>
