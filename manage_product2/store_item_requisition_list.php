<?php

include '../lib/DbManager.php';
include "../body/header.php";
?>
<script type="text/javascript" src="include.js"></script>

<div class="easyui-layout" style="width:1100px; margin: auto; height:550px;">  
    <div data-options="region:'center'" Title='Requisition List of Store Items' style="background-color:white; "> 
        <table id="StoreItemRequisitionList" data-options="fit:true,fitColumns:true"></table>
    </div>
</div>



<?php include("../body/footer.php"); ?>