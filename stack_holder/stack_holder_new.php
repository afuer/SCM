<?php
include '../lib/DbManager.php';
include("../body/header.php");


include'DAL.php';
$DAL = new DAL();


$mode = getParam('mode');
$btn = getParam('save');


//$manage->financeProductApproval('6', 'civil Rajib', '6');




$requisitionId = getParam('requisition_id');


//$var = $DAL->viewData($REQUISITION_ID);


if ($btn == 'submit') {

    SaveWorkFlow($requisitionId, 'civil Rajib');
}
$module = 'civil';





?>
<script src="include.js" type="text/javascript"></script>
<br/>
<div class="easyui-layout" style="width:1100px; margin: auto; height:700px;">  
    <div data-options="region:'center'"  title="New Stack Holder"style="padding: 10px 10px; background-color:white; ">
        <div id="loder" class="datagrid-mask-msg" style="display:none; left: 470.5px; z-index:1;">Processing, please wait ...</div>
        <?php include './ajax_requisition_header.php'; ?>
        <br/>  <hr> <br/>

        <?php
        if ($mode != 'approval_note') {
            ?>
            <form name="stack_holder"  method="POST" action="" class="form" autocomplete="off">

                <?php deligationAdd(); ?>

                <button class="button" type="submit" name="save" value="submit">Submit</button>
                <button style="display:none;" class="button" type="button" name="save" value="remove" onClick="removeStackHolder(<?php echo $REQUISITION_ID . ',' . $MODULE . ',' . $mode; ?>)" >Remove All</button>

                <br/>
            </form>

            <?php
        }

        deligationView($requisitionId, 'civil Rajib');
        ?>







    </div>

    <?php include '../body/footer.php'; ?> 


