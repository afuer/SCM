<?php
include '../lib/DbManager.php';
include '../body/header.php';

include 'employee_qualification.php';

$employee = new employee_qualification();
$employeeId = getParam('employeeId');
$primaryId = getParam('primaryId');

$var = $employee->getDataQualificationId($primaryId);
?>



<script type="text/javascript" src="../public/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="include.js"></script>

<input type="hidden" name="employeeId" id="employeeId" value="<?php echo $employeeId; ?>" />

<div class="easyui-layout" style="width:100%; height:800px;">  
    <div data-options="region:'north'" style="height:50px">Top Part</div>  
    <div data-options="region:'south',split:true" style="height:50px;">Button Part</div>  

    <div data-options="region:'east',split:true" title="East" style="width:250px;">  
        <ul class="easyui-tree" data-options="url:'#', animate:true, dnd:true"></ul>  
    </div> 

    <div data-options="region:'west',split:true" title="West" style="width:200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">  
            <div title="Title1" style="padding:10px;">  
                content1  
            </div>  
            <div title="Title2" data-options="selected:true" style="padding:10px;">  
                content2  
            </div>  
            <div title="Title3" style="padding:10px">  
                content3  
            </div>  
        </div>  
    </div>  
    <div data-options="region:'center',title:'Employee Information',iconCls:'icon-ok'"> 

        <div id="employeeHeader"></div>

        <fieldset class="fieldset">
            <legend> Career Details View  </legend>
                <table class="ui-state-default">
                    <tbody>
                        <tr>
                            <td width="150">Qualification Area:</td> 
                            <td width="250"><?php echo $var->QUALIFICATION_AREA; ?></td>
                            <td width="150">Qualification Title:</td>
                            <td width="250"><?php echo $var->QUALIFICATION_TITLE; ?></td>
                        </tr>
                        <tr>
                            <td >Institute:</td> 
                            <td><?php echo $var->INSTITUTE; ?></td>
                            <td>Result:</td>
                            <td><?php echo $var->RESULT; ?></td>
                        </tr>
                        <tr>
                            <td >Start Date:</td> 
                            <td><?php echo bdDate($var->START_DATE); ?></td>
                            <td>End Date:</td>
                            <td><?php echo bdDate($var->END_DATE); ?></td>
                        </tr>
                    </tbody>
                </table>
           
         
        </fieldset>
         <a href="employee_qualification_edit.php?mode=edit&employeeId=<?php echo $employeeId.'&primaryId='.$primaryId; ?>" class="button" >Update</a>
         <a href="index.php?mode=search&employeeId=<?php echo $employeeId; ?>" class="button" >Back to list</a>

    </div>  


</div> 



<?php include '../body/footer.php'; ?>