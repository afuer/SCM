<?php
include('include.php');



$Costcenterids = rs2array(query("select code,code, name from cost_center_code"));
$WfGroupList = rs2array(query("SELECT workflow_group_id,workflow_name  FROM workflow_group"));
$WorkFlowProcessTypeList = rs2array(query("SELECT WORKFLOW_PROCESS_TYPE_ID,WORKFLOW_PROCESS_NAME  FROM workflow_process_type"));
$StaffMember = find("SELECT ed.FULL_NAME,ed.CARDNO,ed.BRANCH_NAMEID,ed.DEPARTMENT_NAMEID
FROM employee_details AS ed
WHERE ed.CARDNO = '2010441'");


$SqlExpenseType = query("SELECT EXPENSE_TYPE_ID,EXPENSE_TYPE_NAME FROM expense_type");
$SqlWorkFlowType = query("SELECT WORKFLOW_TYPE_ID, WORKFLOW_TYPE_NAME FROM workflow_type");

$MaxRequisitionMainId = NextId('gp_requesition_main', 'REQUISITION_ID');


include("../body/header.php");
?>




<script type="text/javascript">
    $(document).ready(function(){
            
        

        //var cell = $('table#tableId tr:nth-child(' + row + ') td:nth-child(' + col + ')');
            
                
        $('#tableId tr').click(function () {
            var col= 2;
            var index=0;
            var index=$('table tr').index(this);
            $('#tableId tbody > tr:nth-child('+index+')').css('background-color', '#FEFF9F');
            $('#tableId tbody > tr:nth-child('+index+') td:nth-child(' +col+ ')').html(index+col);
                
               
            console.log(index);
        });
        
        
     
        $('#WorkflowTab tbody tr').click(function () {
            var colss = 4;
            var index=0;
            var index=$('#WorkflowTab tbody tr').index(this);
            index+=1;
            
            $('#WorkflowTab tbody > tr:nth-child('+index+') input').change(function(){
                var v=$('#WorkflowTab tbody > tr:nth-child('+index+') .e').val();
                $('#WorkflowTab tbody > tr:nth-child('+index+')').css('background-color', '#FEFF9F');
                $('#WorkflowTab tbody > tr:nth-child('+index+') td:nth-child(' + colss + ')').html(v);
                 console.log(v);
                
            });
            
            
               
           
        });
                
        $('#WmAdd').click(function(){
            
            $('#WorkflowTab tbody>tr:last').clone(true).insertAfter('#WorkflowTab tbody>tr:last').val();
        });
           
    });
            
   
    

        
    

</script>

<table id="tableId" class="ui-state-default">
    <thead>
    <th>ss</th>
    <th>dd</th>
    <th>ff</th>
</thead>
<tbody>
    <tr><td>0</td><td>0</td><td>0</td></tr>
    <tr><td>1</td><td>1</td><td>1</td></tr>
    <tr><td>2</td><td>2</td><td>2</td></tr>
</tbody>
</table>


<br/><br/><br/><br/><br/>
<table class="ui-state-default" id="WorkflowTab" >
    <thead>
    <th width="20">SL</th>
    <th width="150">Procedure By </th>
    <th width="150">Card No</th>
    <th>Employee Name</th>
    <th width="150">Designation</th>
    <th width="100">Action</th>
</thead>
<tbody>
    <tr>
        <td></td>
        <td><?php combobox('ProById[]', $WorkFlowProcessTypeList, '', true); ?></td>                
        <td><input type="text" name="EmployeeId[]" class="e"/> </td> 
        <td class="d"></td>
        <td></td>
        <td></td>
    </tr>
</tbody>

</table>

<table class="ui-state-default" id="WorkflowTab" style="display: none;">
            <thead>
            <th width="20">SL</th>
            <th>Procedure By </th>
            <th width="150">Card No</th>
            <th>Employee Name</th>
            <th width="250">Designation</th>
            <th width="100">Action</th>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td><?php combobox('ProById[]', $WorkFlowProcessTypeList, '', true); ?></td>                
                    <td><input type="text" name="EmployeeId[]" class=""/> </td> 
                    <td></td>
                    <td></td>
                    <td><div onClick='$(this).parent().parent().remove();'>Remove</div></td>
                </tr>
            </tbody>


        </table>


<div  class="float_right"><input type=button id="WmAdd" value='add more'/> </div>

