<?php
include_once '../lib/DbManager.php';
include_once '../body/header.php';
include '../lib/pagination.php';
?>

<div Title='Requisition List' class="easyui-panel" style="width:1000px; height:700px;" >
    <a href="RequisitionList.php" class="button">Requisition List</a>
    <a href="RequisitionPendingList.php" class="button">Requisition Pending List</a>
    <?php
    //include 'RequisitionList.php';
    //include 'RequisitionPendingList.php';
    ?>
</div>
<?php
include '../body/footer.php';

