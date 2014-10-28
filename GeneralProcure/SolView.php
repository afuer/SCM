<?php
include_once '../lib/DbManager.php';
include('SolDAL.php');
$ObjRequestnData = new SolData();


$mode = getParam('mode');
$SearchID = getParam('search_id');
$RowSol = $ObjRequestnData->GetDataSolView($SearchID);

if ($mode == 'delete') {
    $ObjRequestnData->DeletRequest($SearchID);
    echo "<script>location.replace('SolList.php?mode=search');</script>";
}




include '../body/header.php';
?>
<div Title='Payment List' class="easyui-panel" style="height:1000px;" >
    <form action="" method="POST" name='WorkFlowGroup' class="form" autocomplete="off">
        <table class="table">
            <tr>
                <td width="200">Sol Name:</td>
                <td><?php echo $RowSol->SOL_NAME; ?> </td> 
                <td>Sol Code:</td>
                <td><?php echo $RowSol->SOL_CODE; ?> </td> 

            </tr>
            <tr>
                <td>Office Type:</td>
                <td><?php echo $RowSol->OFFICE_NAME; ?></td> 
                <td>Branch/Dept:</td>
                <td><?php echo $RowSol->BRANCH_NAME . $RowSol->DEPARTMENT_NAME; ?> </td> 
            </tr>
            <tr>
                <td>Division:</td>
                <td><?php echo $RowSol->DIVISION_NAME; ?> </td> 
                <td></td>
                <td></td> 
            </tr>
        </table>

        <a class="button" href="Solnew.php?mode=search&search_id=<?php echo $SearchID; ?>">Edit</a>
        <a class="button" href="SolList.php">Sol List</a>
        <a class="button" href="SolNew.php?mode=new">Add New</a>
        </from>
</div>
<?php include '../body/footer.php'; ?>

