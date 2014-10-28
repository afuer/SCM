<?php
include '../lib/DbManager.php';
include '../body/header.php';


$SearchID = getParam('search_id');


if (isSave()) {
    $GroupNameId = getParam('GroupNameId');
    $OfficeTypeID = getParam('OfficeTypeID');
    $BranchDept = getParam('BranchDept');
    $DivisionID = getParam('DivisionID');
    $DesignationId = getParam('DesignationId');
    $WorkFlowTypeId = getParam('WorkFlowTypeId');


    if ($mode == 'new') {

        $MaxGroupDeatilsId = NextId('workflow_group_details', 'workflow_group_details_id');
        $SqlSol = "INSERT INTO workflow_group_details (WORKFLOW_GROUP_DETAILS_ID, WORKFLOW_GROUP_ID, DESIGNATION_ID, BRANCH_DEPT, OFFICE_TYPE_ID, WORKFLOW_PROCESS_TYPE_ID, DIVISION_ID, CREATED_BY, CREATED_DATE) 
                       Values('$MaxGroupDeatilsId','$GroupNameId','$DesignationId','$BranchDept','$OfficeTypeID','$WorkFlowTypeId', '$DivisionID', '$user_name',NOW())";
        sql($SqlSol);

        echo "<script>location.replace('WorkflowGroupDetailsList.php');</script>";
    } else {
        $SQL = "UPDATE workflow_group_details SET
        WORKFLOW_GROUP_ID='$GroupNameId', 
        DESIGNATION_ID='$DesignationId', 
        BRANCH_DEPT='$BranchDept', 
        OFFICE_TYPE_ID='$OfficeTypeID', 
        WORKFLOW_PROCESS_TYPE_ID='$WorkFlowTypeId', 
        DIVISION_ID='$DivisionID', 
        MODIFY_BY='$user_name', 
        MODIFY_DATE=NOW()
        WHERE WORKFLOW_GROUP_DETAILS_ID='$SearchID'";
        sql($SQL);
        echo "<script>location.replace('WorkflowGroupDetailsList.php');</script>";
    }
}

if ($mode = 'search') {
    $var = find("SELECT WORKFLOW_GROUP_ID, DESIGNATION_ID, BRANCH_DEPT, OFFICE_TYPE_ID, WORKFLOW_PROCESS_TYPE_ID, DIVISION_ID FROM workflow_group_details WHERE WORKFLOW_GROUP_DETAILS_ID='$SearchID'");
}


$DivisionList = rs2array(query("SELECT DIVISION_ID, DIVISION_NAME FROM division"));
$DepartmentList = rs2array(query("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM department"));
$OfficeList = rs2array(query("SELECT OFFICE_TYPE_ID, OFFICE_NAME FROM office_type"));
$group_list = rs2array(query("SELECT WORKFLOW_GROUP_ID, WORKFLOW_NAME FROM workflow_group ORDER BY WORKFLOW_NAME"));
$Designation_list = rs2array(query("SELECT DESIGNATION_ID, DESIGNATION_NAME FROM designation ORDER BY DESIGNATION_NAME"));
$WorkFlowProcessTypeList = rs2array(query("SELECT WORKFLOW_PROCESS_TYPE_ID,WORKFLOW_PROCESS_NAME  FROM workflow_process_type"));
$DepartmentList = rs2array(query("SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM department"));
$BranchList = rs2array(query("SELECT BRANCH_ID, BRANCH_NAME FROM branch"));
?>
<fieldset class="fieldset">
    <legend>Add Work Flow Group</legend>

    <form action="" method="POST" name='WorkFlowGroup' class="form" autocomplete="off">
        <table>
            <tr>
                <td width="150">Group Name:</td>
                <td><?php comboBox('GroupNameId', $group_list, $var->WORKFLOW_GROUP_ID, TRUE, 'required'); ?></td> 
            </tr>
            <tr>
                <td width="150">Office Type:</td>
                <td><?php comboBox('OfficeTypeID', $OfficeList, $var->OFFICE_TYPE_ID, TRUE, 'required', 'AjaxBrancDeptWorkflow'); ?></td> 
            </tr>
            <tr>
                <td>Branch/Dept:</td>
                <td id="AjaxBrancDeptWorkflow">
                    <?php
                    if ($var->OFFICE_TYPE_ID == 1) {
                        comboBox('BranchDept', $DepartmentList, $var->BRANCH_DEPT, TRUE, 'required');
                    } else {
                        comboBox('BranchDept', $BranchList, $var->BRANCH_DEPT, TRUE, 'required');
                    }
                    ?> 
                </td> 
            </tr>
            <tr>
                <td>Division:</td>
                <td><?php comboBox('DivisionID', $DivisionList, $var->DIVISION_ID, TRUE, 'required') ?> </td> 

            </tr>
            <tr>
                <td>Designation:</td>
                <td><?php comboBox('DesignationId', $Designation_list, $var->DESIGNATION_ID, TRUE, 'required') ?> </td>  
            </tr>
            <tr>
                <td>Work Flow:</td>
                <td><?php comboBox('WorkFlowTypeId', $WorkFlowProcessTypeList, $var->WORKFLOW_PROCESS_TYPE_ID, TRUE, 'required') ?> </td> 
            </tr>
        </table>

        <button type="submit" name="save" value="save" class="button">Save</button>
        </from>
</fieldset>


<?php
include '../body/footer.php';
?>




