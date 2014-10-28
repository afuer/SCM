<?php

include '../lib/DbManager.php';
include "../body/header.php";
?>
<script type="text/javascript" src="include.js"></script>


<div id="tab" style="height:550px">
    <div title="Requisition List of Store Items" style="padding:10px">
        <table id="StoreItemRequisitionList" data-options="fit:true,fitColumns:true"></table>
    </div>

    <?php if($UserLevelId==8){?>
    <div title="HOIT Approvl List" style="padding:10px">
        <table id="StoreItemRequisitionApprovalList"></table>
        <?php //include './approve_store_product.php'; ?>
    </div>
    <?php }?>
    <!--
    
    <div title="Manage Bill List" style="padding:10px">
        <?php include './store_product_manage_bill.php'; ?>
    </div>
    -->
</div>





<?php include("../body/footer.php"); ?>