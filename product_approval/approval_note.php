<?php
include_once '../lib/DbManager.php';
include '../BudgetAllocation/BudgetAllocationDAL.php';
$ObjRequisitionData = new RequisitionData();
?>





<?php
include '../body/header.php';

$searchId = getParam('comparison_id');
$processDeptId = getParam('processDeptId');

$solList = rs2array(query("SELECT sol_id, sol_code, SOL_NAME FROM sol ORDER BY SOL_NAME"));
$costCenterList = $db->rs2array("SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME");
comboBox('costCenter', $costCenterList, '', TRUE, 'autoHight');
comboBox('sol', $solList, '', TRUE, 'autoHight');

//$req_info=  find("SELECT requisition_id FROM price_comparison_pro_req_qty WHERE price_comparison_id=27 GROUP BY requisition_id");

$requisition = find("SELECT pcq.requisition_id, PROCESS_DEPT_ID 
FROM price_comparison_pro_req_qty pcq
INNER JOIN requisition r ON r.REQUISITION_ID=pcq.requisition_id
WHERE price_comparison_id='$searchId' GROUP BY pcq.requisition_id");

$budgetAllocation = findValue("SELECT COUNT(*) FROM budget_allocation WHERE REQUISITION_ID='$requisition->requisition_id'");

function requisitionWiseProductView($SearchId) {
    $sql = "SELECT r.REQUISITION_NO, rd.REQUISITION_DETAILS_ID,p.PRODUCT_ID,p.PRODUCT_NAME AS PRODUCT_NAME, 
            u.UNIT_TYPE_NAME,rd.UNIT_PRICE,rd.QTY,rd.USER_COMMENT
            FROM requisition AS r
            LEFT JOIN requisition_details AS rd ON r.REQUISITION_ID = rd.REQUISITION_ID
            LEFT JOIN product As p ON p.product_id =rd.PRODUCT_ID 
            LEFT JOIN unit_type AS u ON u.UNIT_TYPE_ID =p.unit_type_ID
            WHERE r.REQUISITION_ID = '$SearchId'
            ORDER BY r.REQUISITION_ID";
    return query($sql);
}

//$rec_com = find("select * from price_comparison where comparisonid='$searchId'");

$btn = getParam('addApproval');
$Module = 'Product Approval';

if (isSave()) {
    $footer = getParam('footer');
    $body = getParam('body');
    $subject = getParam('subject');
    $cc = getParam('cc');
    $date = getParam('date');
    $ref = getParam('ref');


    $approvalComment = getParam('comments');

    $btn = getParam('addApproval');
    /*
     * Inserts into workflow. No calculation is needed here. Only the history of approval persons are preserved here.
     */



    $insert_sql = "INSERT INTO requisition_approval (CS_ID, REF, DATE, CC, `SUBJECT`, BODY, FOOTER, `STATUS`, CREATED_BY, CREATED_DATE) 
                    VALUES('$searchId', '$ref', '$date', '$cc', '$subject', '$body', '$footer',  '1', '$employeeId', NOW())";
    query($insert_sql);
    $csCode = OrderNo($searchId);

    sql("UPDATE price_comparison SET comparative_code='$csCode' WHERE comparisonid='$searchId'");

    SaveWorkFlow("$searchId", "$Module", "$employeeId");

    include '../GeneralProcure/manager.php';
    $manage = new WorkFlowManager();
    //if ($processDeptId == 5) {
    $manage->SendHeadOfIT($searchId);
    //} else {
    $manage->SendHeadOfProcure($searchId);
    //}

    echo "<script>location.replace('index.php');</script>";
}
?>

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

<script src="nicEdit.js"></script>

<script type="text/javascript">
    bkLib.onDomLoaded(function() {
        nicEditors.allTextAreas();
    });


    function addCc(obj) {
        var requisitionId = obj.attr('requisitionId'),
                itemrow = obj.closest('tr'),
                productId = itemrow.find('.product').val(),
                supplierId = obj.attr('supplierId');

        var newtr = '<div class="fc left-td float-left">\n\
                <select class="costCenter" name="CostCenter" placeholder="Cost Center">' + $('#costCenterID').html() + '</select>\n\
                <select class="sol" name="sol" placeholder="Sol">' + $('#solID').html() + '</select>\n\
                <input type="text" class="Amount" name="Amount" placeholder="Percentage"/>\n\
                <input type="button" name="save" id="addFlow" supplierId="' + supplierId + '" productId="' + productId + '" value="Save" searchId="' + requisitionId + '"  class="button" onClick="ccSaveProductWise($(this))"/>\n\
            </div>';

        obj.closest('tr').next('tr').find('.subTd').append(newtr);
    }


    function ccRemoveProductWise(obj) {
        var itemrow = obj.closest('tr');
        var searchId = obj.attr('searchId');
        //alert(searchId);
        //return;

        $.ajax({
            url: "../GeneralProcure/ajaxCcRemove.php?searchId=" + searchId,
            type: "GET",
            contentType: "application/json",
            dataType: "text",
            success: function(data)
            {
                alert(data);
                var result = JSON.parse(data);
                if (result.success) {
                    alert('Remove Successfully');
                    obj.parent().parent().remove();
                }
            }
        });
    }

    function ccSaveProductWise(obj) {
        var itemrow = obj.closest('tr');
        var productId = itemrow.find('#addFlow').attr('productId'),
                supplierId = obj.attr('supplierId'),
                costCenterId = itemrow.find('select.costCenter option:selected').val(),
                costCenterAmount = itemrow.find('input[name="Amount"]').val(),
                SearchId = itemrow.find('#addFlow').attr('searchId'),
                costCenterName = itemrow.find('select.costCenter option:selected').html(),
                solName = itemrow.find('select.sol option:selected').html();

//        alert(supplierId);
//        return;

        var RowItem = {
            "requisitionId": "",
            "productId": "",
            "costCenterId": "",
            "costCenterAmount": "",
            "supplierId": ""
        };

        RowItem.requisitionId = SearchId;
        RowItem.productId = productId;
        RowItem.supplierId = supplierId;
        RowItem.costCenterId = costCenterId;
        RowItem.costCenterAmount = costCenterAmount;

        var jsonstr = JSON.stringify(RowItem);

        $.ajax({
            url: "../GeneralProcure/ajaxCcAddProductWise.php",
            data: "data=" + jsonstr,
            type: "GET",
            contentType: "application/json",
            dataType: "text",
            success: function(data)
            {
                //alert(data);
                var result = JSON.parse(data);
                if (result.success) {
                    obj.closest('tr td').find('.fc').html('<div class="float-left" style="width: 250px; text-align: left; padding-left:20px;">' + costCenterName + '</div>\n\
                        <div class="float-left" style="width: 150px; text-align: left; padding-left:10px;">' + solName + '</div>\n\
                        <div class="float-left" style="text-align: left; padding-left:50px;">' + costCenterAmount + '</div>\n\
                        <div class="float-right" style="padding-left:50px;" searchId="' + result.Id + '"  class="button" onClick="ccRemoveProductWise($(this))">Remove</div>');
                }
            }
        });
    }

</script>

<script src="include.js" type="text/javascript"></script>

<div class="easyui-layout" style="margin: auto; height:1200px;">  
    <div data-options="region:'center'" Title='Create Approval Note' style="padding: 10px 10px; background-color:white; "> 

        <form action="" method="POST" class="formValidate">
            <input type="hidden" name="comparison_id" value="<?php echo $searchId; ?>"/>
            <input type="hidden" name="processDeptId" value="<?php echo $requisition->PROCESS_DEPT_ID; ?>"/>





            <table class="ui-state-default">
                <tr>
                    <td>Ref:</td>
                    <td><input type="text" name='ref'/></td>
                    <td>Date:</td>
                    <td><input type="text" name="date" value="<?php echo lasDayMonth(); ?>" class="easyui-datebox required" data-options="formatter:myformatter,parser:myparser"/></td>
                </tr>
            </table>

            <br/>
            <h4>To</h4>
            <br/>
            <div class="fc"><textarea style="width:800px;" name="cc"></textarea></div>
            <br/>


            <h4>Subject</h4>
            <br/>
            <textarea class="fc" name="subject" style="width:800px;; height:100px;">Subject: Limit of Cash </textarea>
            <br/>
            <h4>Letter Body</h4>
            <br/>
            <textarea name="body" style="width:800px; height:200px;">Dear Sir, </textarea>
            <br>
            <br>

            <?php
            if ($budgetAllocation > 0) {

                $sl = 1;
                $CcList = $ObjRequisitionData->get_cc_list_by_requisition_id($requisition->requisition_id);
                while ($RowCc = fetch_object($CcList)) {
                    $year = date('Y');
                    ?>
                    <h3>Cost Center: <?php echo $RowCc->COST_CENTER_NAME . ' - ' . $RowCc->soleName; ?></h3>
                    <table class="ui-state-default"  style="width: 95%;">
                        <thead>
                        <th width = "20">SL</th>
                        <th>Product</th>
                        <th>GL Head</th>
                        <th width = "100">Bill Amount</th>
                        <th width = "150">Tax Info</th>
                        <th width = "100">Tax Amount</th>
                        <th width = "100">Vat Amount</th>
                        <th width = "100">Net Payable</th>
                        <th width="100">Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            $IncomingData = $ObjRequisitionData->GetDataBudgetConfig($requisition->requisition_id, $RowCc->REQUISITION_CC_ID);
                            while ($Row = fetch_object($IncomingData)) {
                                $TaxAmount = ($Row->TOTAL * $Row->GlTax) / 100;
                                $VatAmount = ($Row->TOTAL * $Row->GlVat) / 100;
                                $grand_total+=$Row->PAYABLE;
                                ?>
                                <tr>
                                    <td><?php echo $sl; ?>.<input type="hidden" name="AllocationID[<?php echo $Row->BUDGET_ALLOCATION_ID; ?>]" value="<?php echo $Row->BUDGET_ALLOCATION_ID; ?>" /></td>
                                    <td><?php echo $Row->productName; ?></td>
                                    <td><?php echo $Row->GL_ACCOUNT_ID . '->' . $Row->GL_ACCOUNT_NAME; ?></td>
                                    <td align="right"><?php echo $Row->TOTAL; ?></td>
                                    <td align="right"><?php echo $Row->TAX_TYPE; ?></td>
                                    <td align="right"><?php echo $Row->VAT; ?></td>
                                    <td align="right"><?php echo $Row->TAX; ?></td>
                                    <td align="right"><?php echo $Row->PAYABLE; ?></td>
                                    <td><a target="_blank" href="../ManagementBudget/BudgetHistory.php?cc_id=<?php echo $RowCc->REQUISITION_CC_ID; ?>&year_of=<?php echo $year; ?>&gl_id=<?php echo $Row->GL_ACCOUNT_ID; ?>">View Budget</a></td>
                                </tr>

                                <?php
                                $sl++;
                            }
                            ?>
                        </tbody>
                    </table>

                    <br/>
                <?php } ?>

                <hr>
                <table class="">
                    <tr>
                        <td align="left"></td>
                        <td width="900"></td>
                        <td>Grand Total: </td>
                        <td align="right"><b><?php echo formatMoney($grand_total); ?></b></td>
                    </tr>
                </table>
                In Word: <b><?php echo convert_number_word($grand_total) . 'Taka Only'; ?></b><b/><b/>

                <?php
            } else {
                ?>
                <br/>
                <table class="ui-state-default">
                    <thead>
                        <tr>
                            <th field='1'>SL.</th>
                            <th field='2' width='250'>Product Name</th>
                            <th field='3' width='250'>Selected Supplier</th>
                            <th field='4'>Qty</th>
                            <th field='5'>Rate</th>
                            <th field='6'>Total</th>
                            <th width="80">Action</th>
                        </tr>
                    </thead>

                    <?php
                    $supplierListSQL = "SELECT  pc.detailsid, SUPPLIER_NAME, pc.supplier_id, pc.quantity, 
                    pcq.rate, pcq.product_id, sl, p.PRODUCT_NAME, unite_price, 
                    requisition_id, pc.supplier_id
                    FROM price_comparison_details pc
                    LEFT JOIN product p ON p.PRODUCT_ID=pc.productid
                    LEFT JOIN supplier sp ON sp.SUPPLIER_ID = pc.supplier_id
                    LEFT JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pc.comparison_id AND pcq.product_id=pc.productid
                    WHERE pc.comparison_id='$searchId' AND selected=1 ORDER BY sp.SUPPLIER_NAME";
                    $resultSupp = query($supplierListSQL);
                    while ($resultObj = fetch_object($resultSupp)) {
                        //$unit_price = findValue("SELECT unite_price FROM price_comparison_details WHERE comparison_id='$searchId' AND productid='$row->productid' AND sl='$resultObj->sl'");

                        $grand_total+=($resultObj->quantity * $resultObj->unite_price);
                        ?>
                        <tr style="font-weight: bold;">
                            <td><?php echo++$SL1; ?></td>
                            <td><?php echo $resultObj->PRODUCT_NAME; ?><input type="hidden" name="product[]" class="product" value="<?php echo $resultObj->product_id; ?>" /></td>
                            <td><?php echo $resultObj->SUPPLIER_NAME; ?></td>
                            <td align="right"><?php echo $resultObj->quantity; ?></td>
                            <td align="right"><?php echo formatMoney($resultObj->unite_price); ?></td>
                            <td><?php echo formatMoney($resultObj->cs_qty * $resultObj->unite_price); ?></td>
                            <td><button type="button" supplierId="<?php echo $resultObj->supplier_id; ?>" requisitionId="<?php echo $resultObj->requisition_id; ?>" onclick="addCc($(this));">Add CC</button></td>
                        </tr>
                        <tr class="trSub"><td colspan="7" class="subTd"></td></tr>
                        <tr>
                            <td colspan="7" style="" class="">
                                <?php
                                $a = "SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, 
                                        s.SOL_NAME, rcc.CC_PERCENT, rcc.REQUISITION_CC_LIST_ID
                                        FROM requisition_cc_list rcc
                                        #INNER JOIN price_comparison_pro_req_qty pcq ON pcq.requisition_id=rcc.REQUISITION_ID AND pcq.product_id=rcc.PRODUCT_ID
                                        INNER JOIN price_comparison_details pcd ON pcd.productid=rcc.PRODUCT_ID AND pcd.supplier_id=rcc.SUPPLIER_ID
                                        LEFT JOIN cost_center cc ON cc.COST_CENTER_ID=rcc.REQUISITION_CC_ID
                                        LEFT JOIN sol s ON s.SOL_ID=rcc.SOL_ID
                                        WHERE rcc.REQUISITION_ID='$resultObj->requisition_id' AND rcc.PRODUCT_ID='$resultObj->product_id' AND 
                                            pcd.supplier_id='$resultObj->supplier_id' AND selected=1";
                                $CC = query("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, 
                                        s.SOL_NAME, rcc.CC_PERCENT, rcc.REQUISITION_CC_LIST_ID
                                        FROM requisition_cc_list rcc
                                        INNER JOIN price_comparison_details pcd ON pcd.productid=rcc.PRODUCT_ID
                                        LEFT JOIN cost_center cc ON cc.COST_CENTER_ID=rcc.REQUISITION_CC_ID
                                        LEFT JOIN sol s ON s.SOL_ID=rcc.SOL_ID
                                        WHERE rcc.REQUISITION_ID='$resultObj->requisition_id' AND rcc.PRODUCT_ID='$resultObj->product_id' AND 
                                         selected=1");
                                while ($row = mysql_fetch_object($CC)) {
                                    echo "<div id='ccDiv' productCcList='1' class='productCcList' style='height: 15px; border-bottom: 1px dotted gray; text-align: left; padding: 2px 0px;'>
                                            <div class='float-left' style='width: 400px; text-align: left; padding-left:50px;'>$row->COST_CENTER_CODE-$row->COST_CENTER_NAME</div>
                                            <div class='float-left' style='width: 100px; text-align: left; padding-left:10px;'>$row->SOL_NAME</div>
                                            <div class='float-left' style='text-align: left; padding-left:50px;'>" . formatMoney($row->CC_PERCENT) . "<button type='button' searchId='$row->REQUISITION_CC_LIST_ID' onclick='ccRemoveProductWise($(this));'><b>Remove CC</b></button></div>
                                          </div>";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr style="background-color:gainsboro;">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td colspan="2"><b>Grand Total: </b></td>
                        <td><b><?php echo formatMoney($grand_total); ?></b></td>                
                        <td></td>
                    </tr>
                </table>
                In Word: <b><?php echo convert_number_word($grand_total) . 'Taka Only'; ?></b>
                <?php if ($UserLevelId == 5 || $UserLevelId == 7) { ?>
                    <a class='button' href='../BudgetAllocation/BudgetAllocationNew.php?search_id=<?php echo $requisition->requisition_id; ?>' target="_blank"> GL Allocation </a>
                    <?php
                }
            }
            ?>

            <br/><br/>
            <h4>Bottom Text</h4>
            <br/>
            <textarea name="footer" style="width:800px; height:50px;">Your Replay............. </textarea>

            <br/>

            <?php
            deligationAdd();

//deligationView($searchId, "$Module");
            ?>



            <h2>Approval History</h2>
            <table class="easyui-datagrid">
                <thead>
                    <tr>
                        <th field='1' width="20"><b>SL.</b></th>
                        <th field='2' width="100"><b>Date</b></th>
                        <th field='3'>Employee Name</th>
                        <th field='4' width="150"><b>Designation</b></th>
                        <th field='5'><b>Comments</b></th>
                    </tr>
                </thead>
                <?php
                $sql_his = "SELECT wm.WORKFLOW_MANAGER_ID, emp.FIRST_NAME, emp.CARD_NO, 
                            emp.LAST_NAME, dis.DESIGNATION_NAME, wm.CREATED_DATE, APPROVAL_COMMENT 
                            FROM workflow_manager wm 
                            LEFT JOIN employee emp ON wm.EMPLOYEE_ID = emp.EMPLOYEE_ID
                            LEFT JOIN designation dis ON dis.DESIGNATION_ID=emp.DESIGNATION_ID
                            WHERE wm.REQUISITION_ID='$requisitionId'";

                $query_his = query($sql_his);

                $num_rows = mysql_num_rows($query_his);
                if ($num_rows > 0) {
                    while ($rec_h = fetch_object($query_his)) {
                        $sn++;
                        ?>
                        <tr>
                            <td><?php echo $sn . "."; ?></td>
                            <td><?php echo bddate($rec_h->CREATED_DATE); ?></td>
                            <td><?php echo $rec_h->FIRST_NAME . ' ' . $rec_h->LAST_NAME . '(' . $rec_h->CARD_NO . ')'; ?></td>
                            <td><?php echo $rec_h->DESIGNATION_NAME; ?></td>
                            <td><?php echo $rec_h->APPROVAL_COMMENT; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No Record Foud</td></tr>";
                }
                ?>
            </table>
            <br/>
            <?php
            if ($rec_com->status < 3) {
                ?>


                <table width="100%">
                    <tr>
                        <td valign="top" width="100">Comments: </td>
                    </tr>
                    <tr>
                        <td><textarea style="width:800px;" name="comments" rows="4" cols="48"></textarea></td>
                    </tr>
                </table>
                <input type="submit" name="save" value="Send Finance"/> 

                <a class="button" href="../manage_product2/evaluation_statement.php?comparison_id=<?php echo $searchId; ?>" target="_blank">View CS</a>

                <?php
            }
            ?>
        </form>
        <br>
    </div>
</div>

<?php include '../body/footer.php'; ?>