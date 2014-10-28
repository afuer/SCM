<?php
include '../lib/DbManager.php';
include '../body/header.php';

include 'employee_career.php';

$employee = new employee_career();
$employeeId = getParam('employeeId');

$var = $employee->getDataCareerInfo($employeeId);


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
        <a class="button" href="employee_career_new.php?mode=new&employeeId=<?php echo $employeeId; ?>">New</a>
        <table class="ui-state-default">
            <thead>
            <th width="20">Ser</th>
            <th >Organization Name</th>
            <th width="100">Designation</th>
            <th width="20">Year Of Experience</th>
            <th width="100">Career Start Date</th>
            <th width="100">Career End Date</th>
            <th width="100">Status</th>
            <th width="130">Action</th>

            </thead>

            <tbody>
                <?php while ($row = fetch_object($var)) { ?>
                    <tr>
                        <td><?php echo ++$sl; ?>.</td>
                        <td><?php echo $row->ORGANIZATION_NAME; ?></td>
                        <td><?php echo $row->DESIGNATION_NAME; ?></td>
                        <td><?php echo $row->YEAR_OF_EXPERIENCE; ?></td>
                        <td><?php echo bdDate($row->CAREER_START_DATE); ?></td>
                        <td><?php echo bdDate($row->CAREER_END_DATE); ?></td>
                        <td><?php echo $row->CAREER_STATUS; ?></td>
                        <td>
                            <a style="color:blue;" href="employee_career_view.php?mode=search&employeeId=<?php echo $employeeId.'&careerId='.$row->EMPLOYEE_CAREER_INFO_ID; ?>">view</a>
                            <a style="color:blue;" href="employee_career_edit.php?mode=edit&employeeId=<?php echo $employeeId.'&careerId='.$row->EMPLOYEE_CAREER_INFO_ID; ?>">Correction</a>
                            <a style="color:blue;" href="employee_career_save.php?mode=remove&employeeId=<?php echo $employeeId.'&careerId='.$row->EMPLOYEE_CAREER_INFO_ID; ?>">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>


            </tbody>

        </table>


    </div>  


</div> 
<div id="employeeLinks1"> 

    <a class="button" onclick="careerInfo();">Career Details</a>
    <a  class="button" onclick="professionalQualification();">professional Qualifications</a>

</div>


<?php include '../body/footer.php'; ?>