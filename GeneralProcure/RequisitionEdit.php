<?php
include '../lib/DbManager.php';
include("../body/header.php");
include("RequisitionDAL.php");




$solList = GetSole();
$WorkFlowProcessTypeList = rs2array(query("SELECT WORKFLOW_PROCESS_ID,WORKFLOW_PROCESS_NAME  FROM workflow_process_type"));
$costCenterList = $db->rs2array("SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME");

comboBox('costCenter', $costCenterList, '', TRUE, 'autoHight');
comboBox('sol', $solList, '', TRUE, 'autoHight');

?>

<script type="text/javascript">
    function addCc(obj) {
        var requisitionId = getParam('search_id');

        var itemrow = obj.closest('tr');
        var productId = itemrow.find('.product').val();

        var newtr = '<div class="fc left-td float-left">\n\
        <select class="costCenter" name="CostCenter" placeholder="Cost Center">' + $('#costCenterID').html() + '</select>\n\
        <select class="sol" name="sol" placeholder="Sol">' + $('#solID').html() + '</select>\n\
        <input type="text" class="Amount" name="Amount" placeholder="Amount"/>\n\
            <input type="button" name="save" id="addFlow" productId="' + productId + '" value="Save" searchId="' + requisitionId + '"  class="button" onClick="ccSaveProductWise($(this))"/>\n\
        </div>';

        obj.closest('tr').next('tr').find('.subTd').append(newtr);
        //allAutocomplate();

        //console.log(product);
    }

    function ccSaveProductWise(obj) {

        var productId = $('#addFlow').attr('productId'),
                costCenterId = $('select.costCenter option:selected').val(),
                costCenterAmount = $('input[name="Amount"]').val(),
                SearchId = $('#addFlow').attr('searchId');

        var RowItem = {
            "requisitionId": "",
            "productId": "",
            "costCenterId": "",
            "costCenterAmount": ""
        };


        RowItem.requisitionId = SearchId;
        RowItem.productId = productId;
        RowItem.costCenterId = costCenterId;
        RowItem.costCenterAmount = costCenterAmount;

        var jsonstr = JSON.stringify(RowItem);

        $.ajax({
            url: "ajaxCcAddProductWise.php",
            data: "data=" + jsonstr,
            type: "GET",
            contentType: "application/json",
            dataType: "text"
        });





    }



</script>

<style type="text/css">


    .autoHight{position: fixed; bottom: -100px;}

    .cc{background-color: #EEFFEE;}
    div.cc{padding: 5px 0px;}
    table.tableSub{margin: 5px 5px;}
    #productGrid{
        border-collapse: collapse;
    }
    .left-td{margin-left: 15px; color: gray;}

    table#productGrid tr td {
        border-bottom: 1px dotted #DADADA;
        font-size: 10pt;
        padding: 2px 10px;
    }
    .float-left{padding: 1px 5px;}


    table#productGrid th {
        border: 1px solid #CCCCCC;
        color: black;
        cursor: pointer;
        height: 25px;
        padding-left: 5px;
        text-align: center;
        text-transform: capitalize;
    }


    form table:not(.ui-state-default) tr td input[type="text"] {
        border-color: gainsboro;
        height: 20px;
        width: 200px;
    }


    form table tr td select {
        border-color: gainsboro;
        height: 25px;
        max-width: 250px;
        min-width: 200px;
    }
</style>

<script type="text/javascript" src="../public/js/jquery.calculation.js"></script>
<script src="Requisition.js"></script>


<div Title='Requisition View' class="easyui-panel" style="width:1000px; height:1500px;" > 
    <form action="" method="POST" name='requisition' class="form" autocomplete="off">


        <fieldset class="fieldset">
            <legend>Requisition Information</legend>
            <table>
            <tr> 
                <td width="80">PR No:  </td>
                <td width="200"><?php echo $ResultRequisitionMain->REQUISITION_NO; ?></td>
                <td width="150">Staff Member:</td>
                <td><?php echo $ResultRequisitionMain->FULL_NAME . ' (' . $ResultRequisitionMain->CARD_NO . ')'; ?></td>
            </tr>
            <tr>
                <td>Date:</td>
                <td><?php echo bddate($ResultRequisitionMain->REQUISITION_DATE); ?></td>
                <td>Location :</td>
                <td><?php echo user_location($ResultRequisitionMain->CREATED_BY); ?></td>
            </tr>
            <tr>
                <td>Req From:</td>
                <td><?php echo $ResultRequisitionMain->CREATED_BY; ?></td>
                <td>Priority:</td>
                <td><?php echo $ResultRequisitionMain->PRIORITY_NAME; ?></td>
            </tr>                    
        </table>
        </fieldset>
        <br/>
        <?php
        $sql = "SELECT REQUISITION_NO, REQUISITION_ID FROM requisition WHERE PARENT_REQUISITION_ID='$SearchId'";
        $sqlResult = query($sql);

        while ($rowReq = mysql_fetch_object($sqlResult)) {
            ?>
            <fieldset class="fieldset"> 
                <legend>Requisition No: <?php echo $rowReq->REQUISITION_NO; ?></legend>
                <table class="ui-state-default" >
                    <thead>
                    <th field="1" width="30">SL.</th>
                    <th field="2">Requisition No</th>
                    <th field="2">Product</th>
                    <th field="3" width="100">Qty</th>
                    <th field="4" width="100">Price</th>
                    <th field="5" width="100">Total</th>
                    <th field="6" width="150">Remark</th>
                    <th width="100">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        $ResultProduct = requisitionWiseProductView($rowReq->REQUISITION_ID);

                        while ($ProductRow = fetch_object($ResultProduct)) {
                            $sum = $ProductRow->UNIT_PRICE * $ProductRow->QTY;
                            ?>
                            <tr style="font-weight: bold;">
                                <td><?php echo++$j, '.'; ?></td>
                                <td><?php echo $ProductRow->REQUISITION_NO; ?><input type="hidden" name="product[]" class="product" value="<?php echo $ProductRow->PRODUCT_ID; ?>" /></td>  
                                <td><?php echo $ProductRow->PRODUCT_NAME; ?></td>  
                                <td align="center"><?php echo $ProductRow->QTY . ' (' . $ProductRow->UNIT_TYPE_NAME . ')'; ?></td>
                                <td align="right"><?php echo $ProductRow->UNIT_PRICE; ?> </td>
                                <td align="right"> <?php echo formatMoney($sum); ?> </td>      
                                <td align="center" ><?php echo $ProductRow->USER_COMMENT; ?></td>
                                <td><button type="button" requisitionId="<?php ?>" onclick="addCc($(this));">Add CC</button></td>
                            </tr>
                            <tr class="trSub"><td colspan="7" class="subTd"></td></tr>
                            <tr>
                                <td colspan="7" style="" class="">
                                    <?php
                                    $CC = query("SELECT cc.COST_CENTER_NAME, s.SOL_NAME, rcc.CC_AMOUNT 
                                    FROM requisition_cc_list rcc
                                    LEFT JOIN cost_center cc ON cc.COST_CENTER_ID=rcc.REQUISITION_CC_ID
                                    LEFT JOIN sol s ON s.SOL_ID=rcc.SOL_ID
                                    WHERE REQUISITION_ID='$SearchId' AND PRODUCT_ID='$ProductRow->product_id'");
                                    while ($row = mysql_fetch_object($CC)) {
                                        echo "<div style='height: 15px; border-bottom: 1px dotted gray; text-align: left; padding: 2px 0px;'>
                                    <div class='float-left' style='width: 250px; text-align: left; padding-left:50px;'>$row->COST_CENTER_NAME</div>
                                    <div class='float-left' style='width: 150px; text-align: left; padding-left:50px;'>$row->SOL_NAME</div>
                                    <div class='float-left' style='text-align: left; padding-left:50px;'>" . formatMoney($row->CC_AMOUNT) . "</div>
                                    </div>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            $total += $sum;
                        }
                        ?> 
                    </tbody>
                    <tfoot>
                    <th></th>
                    <th></th>
                    <th colspan="2" align="right">Grand Total</th>
                    <th align="right"><?php echo formatMoney($total); ?></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    </tfoot>
                </table>
            </fieldset>
            <br>

        <?php } ?>

        <br/>

        <fieldset class="fieldset">
            <legend>Work Flow</legend>
            <table>
                <tr>
                    <td width="120">Expense Type: </td>  
                    <td><?php echo $ResultRequisitionMain->EXPENSE_TYPE_NAME; ?></td>
                    <td width="100">Work Flow:</td>
                    <td><?php echo $ResultRequisitionMain->WORKFLOW_TYPE_NAME; ?></td>
                    <td width="150"><?php echo $ResultRequisitionMain->WORKFLOW_TYPE_ID == 2 ? 'Reported Person:' : ''; ?></td>
                    <td><?php echo $ResultRequisitionMain->workflow_name; ?></td>
                </tr>           
            </table>

            <?php if ($ResultRequisitionMain->WORKFLOW_TYPE_ID == '1') { ?>
                <table class="ui-state-default" id="WorkflowTab" >
                    <thead>
                    <th width="20">SL</th>
                    <th width="250">Work Flow Process</th>
                    <th width="250">Card No</th>
                    <th width="450">Employee Name</th>
                    <th width="250">Designation </th>
                    <th align="right">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        $sl = 0;
                        while ($RowWorkFlow = fetch_object($ResultWorkFlow)) {
                            ?>
                            <tr id="TrWorkFlowManual">
                                <td align="center" ><?php echo++$sl . '.'; ?></td>
                                <td>
                                    <?php combobox("workflow_process[]", $WorkFlowProcessTypeList, $RowWorkFlow->WORKFLOW_PROCESS_TYPE_ID, true); ?>
                                    <input type="hidden" name="workflow_processID[]" value="<?php echo $RowWorkFlow->GP_REQUISITION_FLOW_LIST_ID; ?>" />
                                </td>
                                <td align="center"><input type="text" value="<?php echo $RowWorkFlow->EMPLOYEE_ID; ?>" id="cardno" name="EmployeeId[]" onchange="EmpInfo($(this))"/></td>
                                <td align="center" id="EmpName"><input type="text" name="EmployeeName[]" class="EmpName" id="EmpName" value="<?php echo $RowWorkFlow->FULL_NAME; ?>" size="50"/></td>
                                <td><input type="text" name="EmployeeDesignID[]" id="designation" class="designation" value="<?php echo $RowWorkFlow->DESIGNATION_ID; ?>"/></td>
                                <td><div class="remove" id="<?php echo $RowWorkFlow->GP_REQUISITION_FLOW_LIST_ID; ?>" >Remove</div></td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
                <button type="button" class="button" title="productTab" onclick="RemoveTableTr('WorkflowTab');">Add More</button>

                <?php
            } else {

                $SqlDefultWorkflow = "SELECT rf.DESIGNATION_ID, rf.REQUISITION_ID, d.ISAPPROVAL,
                d.DESIGNATION_NAME, wpt.WORKFLOW_PROCESS_ID, ed.FULL_NAME, ed.CARD_NO,
                di.DIVISION_NAME

                FROM requisition_flow_list AS rf  
                LEFT JOIN workflow_process_type AS wpt ON wpt.WORKFLOW_PROCESS_ID=rf.WORKFLOW_PROCESS_TYPE_ID
                LEFT JOIN employee AS ed ON ed.EMPLOYEE_ID=rf.EMPLOYEE_ID
                LEFT JOIN designation AS d ON d.DESIGNATION_ID=ed.DESIGNATION_ID
                LEFT JOIN division AS di ON di.DIVISION_ID=ed.DIVISION_ID
                WHERE rf.REQUISITION_ID = '$SearchId' ORDER BY rf.WORKFLOW_PROCESS_TYPE_ID";
                $result = query($SqlDefultWorkflow);
                ?>
                <table class="ui-state-default" id="AjaxDefultWorkFlow">
                    <thead>
                    <th width="20">SL</th>
                    <th width="250">Work Flow Process</th>
                    <th width="450">Div/Dep/Branch</th>
                    <th width="250">Designation </th>
                    <th align="right">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($DefultRows = fetch_object($result)) {
                            ?>
                            <tr>
                                <td><?php echo $i . '.'; ?></td>
                                <td><?php combobox('workflow_process[]', $WorkFlowProcessTypeList, $DefultRows->WORKFLOW_PROCESS_TYPE_ID, true); ?></td>
                                <td>
                                    <input type="hidden" name="EmployeeId[]" class="cardno" value="<?php echo $DefultRows->CARDNO; ?>"/>
                                    <?php echo $DefultRows->DIVISION_NAME . '->' . $DefultRows->DEPARTMENT_NAME; ?>
                                </td>
                                <td><?php echo $DefultRows->DESIGNATION_NAME; ?></td>
                                <td><div class='remove' onClick='$(this).parent().parent().remove();'>Remove</div></td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </tbody>

                </table>
                <button type="button" class="button" onclick="RemoveTableTr('AjaxDefultWorkFlow');">Add More</button>

            <?php } ?> 

        </fieldset>
        <br/>

        <table>
            <tr>
                <td width="200">Management Approval: </td>
                <td width="150" ><a id="MaFile" href="<?php echo $ResultRequisitionMain->MANAGEMENT_APPROVE_FILE; ?>" target="_blank">View</a></td>
                <td width="100"><input name="file_upload_done_ma" value="<?php echo $ResultRequisitionMain->MANAGEMENT_APPROVE_FILE; ?>" type="file" /></td>  
                <td colspan="4"></td>
            </tr>
            <tr>
                <td>Board Approval: </td> 
                <td width="150"><a id="BaFile" href="<?php echo $ResultRequisitionMain->BOARD_APPROVE_FILE; ?>"  target="_blank">View</a></td>
                <td><input id="" name="file_upload_done_ba" value="<?php echo $ResultRequisitionMain->BOARD_APPROVE_FILE; ?>" class=""  type="file" /></td>
                <td colspan="4"></td>

            </tr>
        </table>

        <?php file_upload_edit($SearchId, 'requisition', TRUE); ?>

        <br/>
        <fieldset class="fieldset">
            <legend>Comments</legend>
            <table>
                <tbody>
                    <tr>
                        <td>Specification:</td>
                        <td><textarea name="Specification" value="<?php echo $ResultRequisitionMain->SPECIFICATION; ?>"><?php echo $ResultRequisitionMain->SPECIFICATION; ?></textarea></td>
                        <td>Justification:</td>
                        <td><textarea name="Justification" value="<?php echo $ResultRequisitionMain->JUSTIFICATION; ?>"><?php echo $ResultRequisitionMain->JUSTIFICATION; ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Remark:</td>
                        <td><textarea name="Remark" value="<?php echo $ResultRequisitionMain->REMARK; ?>"><?php echo $ResultRequisitionMain->REMARK; ?></textarea></td>
                        <td></td>
                        <td></td>
                    </tr>                
                </tbody>
            </table>

        </fieldset>


        <button type="submit" name="save" value="save" class="button">Save</button>


    </form>
</div>

<?php include '../body/footer.php'; ?>
