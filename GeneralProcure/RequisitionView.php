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
            <table style="width: 800px;">
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
                    <td><?php echo $ResultRequisitionMain->CARD_NO; ?></td>
                    <td></td>
                    <td></td>
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
                var requisitionId = obj.attr('requisitionId');

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


            function ccRemoveProductWise(obj) {
                var conf = window.confirm('Delete the selected record?');
                if (conf === false) {
                    return false;
                }
                var itemrow = obj.closest('tr');

                var productId = itemrow.find('.float-right').attr('searchId');

                $.ajax({
                    url: "ajaxCcRemoveProductWise.php?searchId=" + productId,
                    type: "GET",
                    contentType: "application/json",
                    dataType: "text",
                    success: function(data)
                    {
                        var result = JSON.parse(data);
                        if (result.success) {
                            obj.parent().remove();
                        }
                    }
                });

            }


            function ccSaveProductWise(obj) {
                var itemrow = obj.closest('tr');
                //var productId = itemrow.find('.product').val();

                var productId = itemrow.find('#addFlow').attr('productId'),
                        costCenterId = itemrow.find('select.costCenter option:selected').val(),
                        solId = itemrow.find('select.sol option:selected').val(),
                        costCenterAmount = itemrow.find('input[name="Amount"]').val(),
                        SearchId = itemrow.find('#addFlow').attr('searchId'),
                        costCenterName = itemrow.find('select.costCenter option:selected').html(),
                        solName = itemrow.find('select.sol option:selected').html();


                if (costCenterId.length === 0) {
                    alert('Plase Select Cost Center');
                    return;
                }
                if (solId.length === 0) {
                    alert('Plase Select Sol');
                    return;
                }
                if (costCenterAmount.length === 0 || costCenterAmount > 100) {
                    alert('Plase Enter Percentage<=100');
                    return;
                }
                var RowItem = {
                    "requisitionId": "",
                    "productId": "",
                    "costCenterId": "",
                    "solId": "",
                    "costCenterAmount": ""
                };


                RowItem.requisitionId = SearchId;
                RowItem.productId = productId;
                RowItem.costCenterId = costCenterId;
                RowItem.costCenterAmount = costCenterAmount;
                RowItem.solId = solId;

                var jsonstr = JSON.stringify(RowItem);

                //alert(jsonstr);

                $.ajax({
                    url: "ajaxCcAddProductWise.php",
                    data: "data=" + jsonstr,
                    type: "GET",
                    contentType: "application/json",
                    dataType: "text",
                    success: function(data)
                    {
                        var result = JSON.parse(data);
                        if (result.success) {
                            //obj.closest('tr').remove();
                            obj.closest('tr td').find('.fc').html('<div class="float-left" style="width: 250px; text-align: left; padding-left:20px;">' + costCenterName + '</div>\n\
                        <div class="float-left" style="width: 150px; text-align: left; padding-left:10px;">' + solName + '</div>\n\
                        <div class="float-left" style="text-align: left; padding-left:50px;">' + costCenterAmount + '</div>\n\
                        <div class="float-right" style="padding-left:50px;" searchId="' + result.Id + '"  class="button" onClick="ccRemoveProductWise($(this))">Remove</div>');
                        }
                    }
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
                    <th field="2">Product</th>
                    <th field="3" width="120">Qty</th>
                    <th field="4" width="80">Price</th>
                    <th field="5" width="120">Total</th>
                    <th field="6" width="100">Remark</th>
                    <th width="80">Action</th>
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
                                <td><?php echo $ProductRow->PRODUCT_NAME; ?><input type="hidden" name="product[]" class="product" value="<?php echo $ProductRow->PRODUCT_ID; ?>" /></td>  
                                <td align="center"><?php echo $ProductRow->QTY . ' (' . $ProductRow->UNIT_TYPE_NAME . ')'; ?></td>
                                <td align="right"><?php echo $ProductRow->UNIT_PRICE; ?> </td>
                                <td align="right"> <?php echo formatMoney($sum); ?> </td>      
                                <td align="center" ><?php echo $ProductRow->USER_COMMENT; ?></td>
                                <td><button type="button" requisitionId="<?php echo $rowReq->REQUISITION_ID; ?>" onclick="addCc($(this));">Add CC</button></td>
                            </tr>
                            <tr class="trSub"><td colspan="7" class="subTd"></td></tr>
                            <tr>
                                <td colspan="7" style="" class="">
                                    <?php
                                    $CC = query("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, 
                                        s.SOL_NAME, rcc.CC_PERCENT, rcc.REQUISITION_CC_LIST_ID
                                        FROM requisition_cc_list rcc
                                        LEFT JOIN cost_center cc ON cc.COST_CENTER_ID=rcc.REQUISITION_CC_ID
                                        LEFT JOIN sol s ON s.SOL_ID=rcc.SOL_ID
                                        WHERE REQUISITION_ID='$rowReq->REQUISITION_ID' AND PRODUCT_ID='$ProductRow->PRODUCT_ID'");
                                    while ($row = mysql_fetch_object($CC)) {
                                        echo "<div id='ccDiv' productCcList='1' class='productCcList' style='height: 15px; border-bottom: 1px dotted gray; text-align: left; padding: 2px 0px;'>
                                            <div class='float-left' style='width: 400px; text-align: left; padding-left:50px;'>$row->COST_CENTER_CODE-$row->COST_CENTER_NAME</div>
                                            <div class='float-left' style='width: 100px; text-align: left; padding-left:50px;'>$row->SOL_NAME</div>
                                            <div class='float-left' style='text-align: left; padding-left:50px;'>" . formatMoney($row->CC_PERCENT) . "</div>
                                            <div class='float-right' style='padding-left:10px;' searchId='$row->REQUISITION_CC_LIST_ID' class='button' onClick='ccRemoveProductWise($(this))'>Remove</div>
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

        <table>


            <tr>
                <td width="200">Management/Board Approval:</td>

                <?php if ($ResultRequisitionMain->APPROVE_FILE_PATH != '') { ?>

                    <td width="100"><b><a href="<?php echo $ResultRequisitionMain->APPROVE_FILE_PATH; ?>" target="_blank"><?php echo $ResultRequisitionMain->APPROVE_FILE_TYPE; ?></a></b></td> 

                <?php } else {
                    ?>

                    <td width="100"> N/A   </td>                        
                <?php }
                ?>                                    

            </tr>         

        </table>
        <br/>


        <?php file_upload_view($SearchId, 'requisition'); ?>


        <fieldset class="fieldset">
            <legend><b>Comments</b></legend>
            <table>
                <tbody>
                    <tr>
                        <td valign="top"><b>Specification: </b></td>
                        <td align="left" width="300">
                            <?php
                            if ($ResultRequisitionMain->SPECIFICATION != '') {
                                echo $ResultRequisitionMain->SPECIFICATION;
                            } else {
                                echo 'N/A';
                            }
                            ?>                   
                        </td>
                        <td valign="top"><b>Justification: </b></td>
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
                        <td valign="top"><b>Remark: </b></td>
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
        <a class="button" href="RequisitionList.php" >Confirm</a>
        <a class="button" href="RequisitionList.php" >Requisition List</a>
    </div>
</div>
<?php include '../body/footer.php'; ?>
