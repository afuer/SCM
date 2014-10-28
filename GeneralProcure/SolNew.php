<?php
include_once '../lib/DbManager.php';
include('SolDAL.php');
$ObjSolData = new SolData();


$mode = getParam('mode');
$SearchID = getParam('search_id');


if ($mode == 'search') {

    $RowSol = $ObjSolData->GetDataSol($SearchID);
}


if (isSave()) {
    $SolName = getParam('SolName');
    $SolCode = getParam('SolCode');

    $DivisionID = getParam('DivisionID');
    $DepartmentID = getParam('DepartmentID');
    $BranchID = getParam('BranchID');
    $OfficeID = getParam('OfficeID');

    $MaxSolId = NextId('sol', 'SOL_ID');



    if ($mode == 'new') {

        $ObjSolData->SaveSol($MaxSolId, $SolName, $SolCode, $DivisionID, $DepartmentID, $BranchID, $OfficeID);
        echo "<script>location.replace('SolView.php?mode=search&search_id=$MaxSolId');</script>";
    } else {
        $ObjSolData->EditSol($SearchID, $SolName, $SolCode, $DivisionID, $DepartmentID, $BranchID, $OfficeID);
        echo "<script>location.replace('SolView.php?mode=search&search_id=$SearchID');</script>";
    }
}

include '../body/header.php';
?>
<div Title='Payment List' class="easyui-panel" style="height:1000px;" >
    <fieldset class="fieldset">
        <legend>Add Sole</legend>

        <form action="" method="POST" name='WorkFlowGroup' class="form" autocomplete="off">
            <table>
                <tr>
                    <td width="150">Sol Name:</td>
                    <td><input type="text" name="SolName" value="<?php echo $RowSol->SOL_NAME; ?>" /> </td> 
                    <td  width="150">Sol Code:</td>
                    <td><input type="text" name="SolCode" value="<?php echo $RowSol->SOL_CODE; ?>" /> </td> 
                </tr>
                <tr>
                    <td>Office Type:</td>
                    <td><?php comboBox('OfficeID', $OfficeList, $RowSol->OFFICE_TYPE_ID, TRUE, 'required', 'AjaxBranchDept'); ?> </td> 
                    <td>Branch/Dept:</td>
                    <td id="AjaxBranchDept"><?php comboBox('DepartmentID', $DepartmentList, $RowSol->DEPARTMENT_ID, TRUE) ?> </td> 
                </tr>
                <tr>
                    <td>Division:</td>
                    <td><?php comboBox('DivisionID', $DivisionList, $RowSol->DIVISION_ID, TRUE, 'required') ?> </td> 
                </tr>
            </table>

            <button type="submit" name="save" value="SaveWorkFlow" class="button">Save</button>
            </from>
    </fieldset>
</div>
<?php include '../body/footer.php'; ?>

