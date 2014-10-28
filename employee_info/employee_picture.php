<?php
include_once '../lib/DbManager.php';
include 'employee.php';

$employee_personal = new employee();

$employeeId = getParam('employeeId');


$var = json_decode($employee_personal->getDataOfficeInfo($employeeId));
?>


<table class="ui-state-default1">
    <tbody>
        <tr>
            <td width="130" >Employee Name:</td>
            <td width="300" > </td>
        </tr>
    <table>
        <tr>
    
            
            <td><input type='file' class='uploadify-button' id='file_upload' />
                <input id="file_upload_done" class="text_field_display" type="text" />
            </td>
        </tr>

        </tbody>

    </table>
    <a class="button" id="employeePersonal" href="#" onclick="editEmployeePic()"  > Modify</a>
