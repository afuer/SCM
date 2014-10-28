<?php
include '../lib/DbManager.php';
include '../body/header.php';

include 'employee_career.php';

$employee = new employee_career();
$employeeId = getParam('employeeId');
$careerId = getParam('careerId');

$var = $employee->getDataCareerId($careerId);
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
                            <td width="150">Organization Name:</td> 
                            <td width="250"><?php echo $var->ORGANIZATION_NAME; ?></td>
                            <td width="150">Designation:</td>
                            <td width="250"><?php echo $var->DESIGNATION_NAME; ?></td>
                        </tr>
                        <tr>
                            <td >Year Of Experience:</td> 
                            <td><?php echo $var->YEAR_OF_EXPERIENCE; ?></td>
                            <td>Career Start Date:</td>
                            <td><?php echo bdDate($var->CAREER_START_DATE); ?></td>
                        </tr>
                        <tr>
                            <td >Career End Date:</td> 
                            <td><?php echo bdDate($var->CAREER_END_DATE); ?></td>
                            <td>Status:</td>
                            <td><?php echo $var->CAREER_STATUS; ?></td>
                        </tr>
                    </tbody>
                </table>
           
         
        </fieldset>
         <a href="employee_career_edit.php?mode=edit&employeeId=<?php echo $employeeId.'&careerId='.$careerId; ?>" class="button" >Update</a>

    </div>  


</div> 



<?php include '../body/footer.php'; ?>