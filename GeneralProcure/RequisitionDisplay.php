<?php
include '../lib/DbManager.php';
include("../body/header.php");
include("RequisitionDAL.php");

$solList = GetSole();
$costCenterList = $db->rs2array("SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME");
comboBox('costCenter', $costCenterList, '', TRUE, 'autoHight');
comboBox('sol', $solList, '', TRUE, 'autoHight');
?>

<div class="easyui-layout" style="margin: auto; height:1200px;">  
    <div Title='Requisition View' data-options="region:'center'" style="background-color:white; padding: 10px 10px;"> 

        <fieldset class="fieldset">
            <legend >Requisition Information</legend>
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
                    <td></td>
                    <td><?php //echo $ResultRequisitionMain->PRIORITY_NAME; ?></td>
                </tr>                    
            </table>
        </fieldset>
        <br/>

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
            table.ui-state-default tr.trSub td.subTd{height: 0px;}
            table.ui-state-default tr.trSub td input.button{ margin: 0px; padding: 0px;}
        </style>

        <script type="text/javascript">
            function addCc(obj) {
                var requisitionId = getParam('search_id');

                var itemrow = obj.closest('tr');
                var productId = itemrow.find('.product').val();


                var newtr = '<div class="fc left-td float-left">\n\
            <select class="costCenter" name="CostCenter" placeholder="Cost Center">' + $('#costCenterID').html() + '</select>\n\
            <select class="sol" name="sol" placeholder="Sol">' + $('#solID').html() + '</select>\n\
            <input type="text" class="Amount" name="Amount" placeholder="Percentage"/>\n\
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
                            </tr>
                            <tr>
                                <td colspan="7" style="" class="">
                                    <?php
                                    $CC = query("SELECT cc.COST_CENTER_NAME, s.SOL_NAME, rcc.CC_PERCENT 
                                    FROM requisition_cc_list rcc
                                    LEFT JOIN cost_center cc ON cc.COST_CENTER_ID=rcc.REQUISITION_CC_ID
                                    LEFT JOIN sol s ON s.SOL_ID=rcc.SOL_ID
                                    WHERE REQUISITION_ID='$rowReq->REQUISITION_ID' AND PRODUCT_ID='$ProductRow->PRODUCT_ID'");
                                    while ($row = mysql_fetch_object($CC)) {
                                        echo "<div style='height: 15px; border-bottom: 1px dotted gray; text-align: left; padding: 2px 0px;'>
                                    <div class='float-left' style='width: 400px; text-align: left; padding-left:50px;'>$row->COST_CENTER_NAME</div>
                                    <div class='float-left' style='width: 100px; text-align: left; padding-left:50px;'>$row->SOL_NAME</div>
                                    <div class='float-left' style='padding-left:10px;'>" . formatMoney($row->CC_PERCENT) . "%</div>
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
            <legend>Work Flow </legend>
            <table>
                <tr>
                    <td width="120">Expense Type:</td>  
                    <td><?php echo $ResultRequisitionMain->EXPENSE_TYPE_NAME; ?></td>
                    <td width="100">Work Flow:</td>
                    <td><?php echo $ResultRequisitionMain->WORKFLOW_TYPE_NAME; ?></td>
                    <td width="150"><?php echo $ResultRequisitionMain->WORKFLOW_TYPE_ID == 2 ? 'Reported Person:' : ''; ?> </td>
                    <td><?php echo $ResultRequisitionMain->workflow_name; ?></td>
                </tr>           
            </table>

            <?php if ($ResultRequisitionMain->WORKFLOW_TYPE_ID == '1') { ?>
                <table class="ui-state-default" id="WorkflowTab" >
                    <thead>
                    <th width="30">S/N</th>
                    <th width="250" align="left">Work Flow Process</th>
                    <th width="450">Employee Name</th>
                    <th width="250">Designation</th>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        $sl = 0;
                        while ($RowWorkFlow = fetch_object($ResultWorkFlow)) {
                            ?>
                            <tr>
                                <td align="center" ><?php echo++$sl . '.'; ?></td>
                                <td ><?php echo $RowWorkFlow->WORKFLOW_PROCESS_NAME; ?></td>
                                <td ><?php echo $RowWorkFlow->FULL_NAME . '(' . $RowWorkFlow->EMPLOYEE_ID . ')'; ?></td>
                                <td> <?php echo $RowWorkFlow->DESIGNATION_NAME; ?></td>

                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            } else {
                $SqlDefultWorkflow = "SELECT rf.DESIGNATION_ID, rf.REQUISITION_ID, d.ISAPPROVAL,
            d.DESIGNATION_NAME, wpt.WORKFLOW_PROCESS_ID, ed.FULL_NAME, 
            ed.CARD_NO, wpt.WORKFLOW_PROCESS_NAME,
            di.DIVISION_NAME

            FROM requisition_flow_list AS rf  
            INNER JOIN workflow_process_type AS wpt ON wpt.WORKFLOW_PROCESS_ID=rf.WORKFLOW_PROCESS_TYPE_ID
            INNER JOIN employee AS ed ON ed.CARD_NO=rf.EMPLOYEE_ID
            INNER JOIN designation AS d ON d.DESIGNATION_ID=rf.DESIGNATION_ID
            LEFT JOIN division AS di ON di.DIVISION_ID=ed.DIVISION_ID
            WHERE rf.REQUISITION_ID = '5'  ORDER BY rf.WORKFLOW_PROCESS_TYPE_ID";
                $result = query($SqlDefultWorkflow);
                ?>
                <table class="ui-state-default" id="AjaxDefultWorkFlow">
                    <thead>
                    <th width="30">S/N</th>
                    <th width="250" align="left">Work Flow Process</th>
                    <th width="450">Div/Dep/Branch</th>
                    <th width="250">Designation</th>
                    </thead>
                    <?php
                    while ($DefultRows = mysql_fetch_object($result)) {
                        ?>
                        <tr>
                            <td><?php echo++$p; ?>.</td>
                            <td><?php echo $DefultRows->WORKFLOW_PROCESS_NAME; ?> </td>                
                            <td><?php echo $DefultRows->DIVISION_NAME . '->' . $DefultRows->DEPARTMENT_NAME; ?></td>
                            <td><?php echo $DefultRows->DESIGNATION_NAME; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>

            <?php } ?> 
        </fieldset>
        <br/>

        <table>
            <tr>
                <td width="200">Management Approval:</td>

                <?php if ($ResultRequisitionMain->MANAGEMENT_APPROVE_FILE_PATH != '') { ?>

                    <td width="100"><a href="<?php echo $ResultRequisitionMain->MANAGEMENT_APPROVE_FILE_PATH; ?>" target="_blank">  View </a> </td> 

                <?php } else { ?>

                    <td width="100"> N/A   </td>                        
                <?php }
                ?>                                    

                <td colspan="4"></td>
            </tr>

            <tr>
                <td width="200">Board Approval:</td>

                <?php if ($ResultRequisitionMain->BOARD_APPROVE_FILE_PATH != '') { ?>

                    <td width="100"><a href="<?php echo $ResultRequisitionMain->BOARD_APPROVE_FILE; ?>" target="_blank"> View </a> </td> 

                <?php } else {
                    ?>

                    <td width="100"> N/A   </td>                        
                <?php }
                ?>                                    

                <td colspan="4"></td>
            </tr>         

        </table>



        <?php file_upload_view($SearchId, 'requisition'); ?>


        <fieldset class="fieldset">
            <legend>Comments</legend>
            <table>
                <tbody>
                    <tr>
                        <td width="150">Specification:</td>
                        <td align="left">
                            <?php
                            if ($ResultRequisitionMain->SPECIFICATION != '') {
                                echo $ResultRequisitionMain->SPECIFICATION;
                            } else {
                                echo 'N/A';
                            }
                            ?>                   
                        </td>
                        <td width="150">Justification:</td>
                        <td align="left"> 
                            <?php
                            if ($ResultRequisitionMain->JUSTIFICATION != '') {
                                echo $ResultRequisitionMain->JUSTIFICATION;
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Remark:</td>
                        <td align="left">
                            <?php
                            if ($ResultRequisitionMain->REMARK != '') {
                                echo $ResultRequisitionMain->REMARK;
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        <td></td>
                        <td></td>

                    </tr>                
                </tbody>
            </table>
        </fieldset>
        <br/>


        <?php
        if ($ResultRequisitionMain->REQUISITION_STATUS_ID > 0) {
            include 'ApprovalHistory.php';
        } else {
            ?>
            <a class="button" href="RequisitionEdit.php?mode=search&search_id=<?php echo $SearchId; ?>">Edit</a> 
        <?php }
        ?>
        <a class="button" href="RequisitionList.php" >Requisition List</a>
        <a class="button" href="requisition_type.php">New Requisition</a>
    </div>
</div>
<?php include '../body/footer.php'; ?>
