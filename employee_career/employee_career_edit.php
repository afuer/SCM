<?php
include '../lib/DbManager.php';
include '../body/header.php';

include 'employee_career.php';

$employee = new employee_career();
$employeeId = getParam('employeeId');
$careerId = getParam('careerId');

$var = $employee->getDataCareerId($careerId);
$designationList = $employee->designationCombo();
$statusList = array(array(1, 'Approved'), array(0, 'Pending'));
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

        <form class="form" id="emCareerEdit" action="employee_career_save.php?mode=edit" method="POST" >
            <input type="hidden" name="employeeId" value="<?php echo $employeeId; ?>" />
            <input type="hidden" name="careerId" value="<?php echo $careerId; ?>" />
            <fieldset class="fieldset">
                <legend> Career Details View  </legend>

                <table class="ui-state-default">
                    <tbody>
                        <tr>
                            <td width="150">Organization Name:</td> 
                            <td width="250"><input type="text" name="ORGANIZATION_NAME" value="<?php echo $var->ORGANIZATION_NAME; ?>"></td>
                            <td width="150">Designation:</td>
                            <td width="250"><?php combobox("DESIGNATION_ID", $designationList, $var->DESIGNATION_ID, true); ?></td>
                        </tr>
                        <tr class="fitem">
                            <td >Year Of Experience:</td> 
                            <td><input type="text" name="YEAR_OF_EXPERIENCE" value="<?php echo $var->YEAR_OF_EXPERIENCE; ?>"></td>
                            <td>Career Start Date:</td>
                            <td><input name="CAREER_START_DATE" class="easyui-datebox" value="<?php echo $var->CAREER_START_DATE; ?>" /></td>
                        </tr>
                        <tr class="fitem">
                            <td >Career End Date:</td> 
                            <td><input name="CAREER_END_DATE" class="easyui-datebox" value="<?php echo $var->CAREER_END_DATE; ?>" /></td>
                            <td>Status:</td>
                            <td><?php combobox("STATUS", $statusList, $var->STATUS, true); ?></td>
                        </tr>
                    </tbody>
                </table>


            </fieldset>
            <button type="submit" name="save" value="SaveRequisition" class="button">Save</button>
        </form>
    </div>  


</div> 



<?php include '../body/footer.php'; ?>