<?php
include '../lib/DbManager.php';
include '../body/header.php';

include 'employee_qualification.php';

$employee = new employee_qualification();

$employeeId = getParam('employeeId');

$var = $employee->getDataQualification($employeeId);


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
        <a class="button" href="employee_qualification_new.php?mode=new&employeeId=<?php echo $employeeId; ?>">New</a>
        <table class="ui-state-default">
            <thead>
            <th width="20">Ser</th>
            <th >Qualification Area</th>
            <th >Qualification Title</th>
            <th width="100">Institute</th>
            <th width="100">Result</th>
            <th width="100">Start Date</th>
            <th width="100">Start Date</th>
            <th width="130">Action</th>

            </thead>

            <tbody>
                <?php while ($row = fetch_object($var)) { ?>
                    <tr>
                        <td><?php echo ++$sl; ?>.</td>
                        <td><?php echo $row->QUALIFICATION_AREA; ?></td>
                        <td><?php echo $row->QUALIFICATION_TITLE; ?></td>
                        <td><?php echo $row->INSTITUTE; ?></td>
                        <td><?php echo $row->RESULT; ?></td>
                        <td><?php echo bdDate($row->START_DATE); ?></td>
                        <td><?php echo bdDate($row->END_DATE); ?></td>
                        <td>
                            <a style="color:blue;" href="employee_qualification_view.php?mode=search&employeeId=<?php echo $employeeId.'&primaryId='.$row->EMPLOYEE_QUALIFICATION_ID; ?>">view</a>
                            <a style="color:blue;" href="employee_qualification_edit.php?mode=edit&employeeId=<?php echo $employeeId.'&primaryId='.$row->EMPLOYEE_QUALIFICATION_ID; ?>">Correction</a>
                            <a style="color:blue;" href="employee_qualification_save.php?mode=remove&employeeId=<?php echo $employeeId.'&primaryId='.$row->EMPLOYEE_QUALIFICATION_ID; ?>">Delete</a>
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