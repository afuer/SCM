<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employee = new employee();
$employeeId = getParam('employeeId');

$var = json_decode($employee->getDataEducation($employeeId));
?>


<table class="ui-state-default1">
    <tbody>
        <tr>
            <td width="130" >Qualification Title :</td>
            <td width="300" > <?php echo $var->QUALIFICATION_TITLE; ?></td>
            <td width="130">Major:</td>
            <td width="300"><?php echo $var->MAJOR; ?></td>
        </tr>
        <tr>
            <td>Passing Year:</td>
            <td><?php echo bdDate($var->PASSING_YEAR); ?></td>
            <td>CGPA/Percentage:</td>
            <td><?php echo $var->CGPA_PERCENTAGE; ?></td>
        </tr>
        <tr>
            <td>Institute Name:</td>
            <td><?php echo $var->INSTITUTE_NAME; ?></td>
            <td>Status:</td>
            <td><?php echo $var->STATUS; ?></td>

        </tr>
        <tr>
            <td>Start Date:</td>
            <td><?php echo bdDate($var->START_DATE); ?></td>
            <td>End Date:</td>
            <td><?php echo bdDate($var->END_DATE); ?></td>
        </tr>
        <tr>
            <td>Career Info:</td>
            <td><?php echo $var->CAREER_INFO; ?></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>

</table>

<a class="button" id="" href="#" onclick="editEmployeeEducationInfo()"  > Modify</a>
