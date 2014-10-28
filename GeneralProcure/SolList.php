<?php
include_once '../lib/DbManager.php';
include('SolDAL.php');
$ObjRequestnData = new SolData();



include("../body/header.php");
include '../lib/pagination.php';
?>

<script type="text/javascript">
    $(document).ready(function() {

        $(".ui-state-default").delegate("tr", "click", function() {
            $(this).addClass("even DTTT_selected").siblings().removeClass("even DTTT_selected");
        });

        $("table.ui-state-default").tablesorter();
        //$("table.ui-state-default").tableFilter();
    });
</script>

<br/>

<?php //grid_top($total, $page, 'SolNew.php?mode=new');  ?>
<div Title='Sol List' class="easyui-panel" style="height:1000px;" >
    <table class="ui-state-default">
        <thead>
        <th width="20">SL</th>
        <th>Sol Name</th>
        <th width="100">Department</th>
        <th width="100">Office Type</th>
        <th width="200">Branch/Dept</th>
        <th colspan="4">Action</th>
        </thead>
        <tbody>
            <?php
            while ($RowOfSolList = fetch_object($request_result)) {
                ?>
                <tr>
                    <td><?php echo++$sl; ?>.</td>
                    <td><?php echo $RowOfSolList->SOL_CODE . '-' . $RowOfSolList->SOL_NAME; ?> </td>
                    <td><?php echo $RowOfSolList->DEPARTMENT_NAME; ?> </td>
                    <td><?php echo $RowOfSolList->OFFICE_NAME; ?> </td>
                    <td><?php echo $RowOfSolList->BRANCH_NAME . $RowOfSolList->DIVISION_NAME; ?> </td>
                    <td align='center' width='5'><?php viewIcon("SolView.php?mode=search&search_id=$RowOfSolList->SOL_ID'"); ?> </td>
                    <td align='center' width='5'><?php editIcon("SolNew.php?mode=search&search_id=$RowOfSolList->SOL_ID'"); ?> </td>
                    <td align='center' width='5'><?php deleteIcon("SolView.php?mode=delete&search_id=$RowOfSolList->SOL_ID"); ?></td>


                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php //pagination($total, $page, '?', $limit);  ?>

<?php include("../body/footer.php"); ?>

<?php
// $RequestID =1;  $ModifyBy = 2010441;  UpdateRequestStatus($RequestID,$ModifyBy) ?>