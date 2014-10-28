<?php
include_once '../lib/DbManager.php';

$object_name = 'employee';
$object_id = strtoupper($object_name) . '_ID';
include '../lib/master_page.php';
include '../body/header.php';




$departmentList = $db->rs2array("SELECT DEPARTMENT_ID,DEPARTMENT_NAME FROM department");
?>
<script type="text/javascript" src="include.js"></script>
<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>

<div class="easyui-layout" style="margin: auto; height:900px;">  
    <div Title='Requisition List' data-options="region:'center'" style="background-color:white; padding: 10px 10px;"> 

        <fieldset>
            <legend>Search</legend>

            <table class="float-left" style="width: 800px;">
                <tr class="fitem">
                    <td width='100'>Date From:</td>
                    <td><input type="text" name="DateFrom" id="DateFrom" class="easyui-datebox" /></td>

                    <td width='100'>Date To:</td>
                    <td><input type="text" name="DateTo" id='DateTo' class="easyui-datebox" /></td>
                </tr>
                <tr>
                    <td>Card No:</td>
                    <td><input type="text" name="cardNo" id='cardNo' /></td>
                    <td>Employee Name:</td>
                    <td><input type="text" name="firstName" id='firstName' /></td>
                </tr>
                <tr>
                    <td>Active:</td>
                    <td><input type="checkbox" name="IsActive" id='IsActive' value="Yes" /></td>
                    <td></td>
                    <td></td>
                </tr>

            </table>
            <div class="fc"></div>
            <button class="easyui-linkbutton fc button" onclick="doSearch();" iconCls="icon-search">Search</button>
            <button type="button" class="easyui-linkbutton button" iconCls="icon-search" onclick="loadWindow();">Rest</button>


        </fieldset>
        <table class="" id="dataGrid"></table>

    </div>  
</div>


<div id="toolbar" style="padding:5px;height:auto">  
    <div id="toolbar">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="AddNew();">Add Employee</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="Edit();">Edit Employee</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="Remove();">Remove Employee</a>
        <button id="editEmployeeInfo" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editEmployeeInfo();">Modify Employee Info</button>
    </div>
</div>

<?php
include '../body/footer.php';
?>