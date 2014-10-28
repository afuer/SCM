<?php 
include_once '../lib/DbManager.php';
include 'employee_career.php';

$employeeId = getParam('employeeId');

$employeeHeader = new employee_career();

$var = json_decode($employeeHeader->getEmployeeHeading($employeeId));

$cardNo = $var->SUPERVISOR_ID;

$supervisor = json_decode($employeeHeader->supervisorHeading($cardNo));

?>
<table class="ui-state-default1">
            <tbody>
                <tr>
                    <td width="130">Employee Name:</td>
                    <td width="300"><?php echo $var->employee_name; ?></td>
                    <td width="130">Employee Id:</td>
                    <td width="300"><?php echo $var->EMPLOYEE_ID; ?></td>
                </tr>
                <tr>
                    <td>Designation:</td>
                    <td><?php echo $var->DESIGNATION_NAME; ?></td>
                    <td>Organization:</td>
                    <td><?php echo $var->ORGANIZATION_NAME; ?></td>
                </tr>
                <tr>
                    <td>Supervisor:</td>
                    <td><?php echo $supervisor->employeeName; ?></td>
                    <td>Grade:</td>
                    <td><?php echo $var->GRADE_NAME; ?></td>
                </tr>

            </tbody>
        </table>
        <br/>
