<?php
include_once '../lib/DbManager.php';
include '../BudgetAllocation/BudgetAllocationDAL.php';
include '../body/header.php';


$ObjRequisitionData = new RequisitionData();

$searchId = getParam('comparison_id');
$search_id = $searchId;

$requisitionId = findValue("SELECT requisition_id FROM price_comparison_pro_req_qty WHERE price_comparison_id='$searchId' GROUP BY requisition_id");

$budgetAllocation = findValue("SELECT COUNT(*) FROM budget_allocation WHERE REQUISITION_ID='$requisitionId'");

$sql = "SELECT APPROVAL_NOTE_ID, CS_ID, REF, ra.DATE, CC, `SUBJECT`, 
    BODY, FOOTER, comparative_code,  ra.`STATUS`, r.REQUISITION_NO
FROM requisition_approval ra
INNER JOIN price_comparison pc ON pc.comparisonid=ra.CS_ID
INNER JOIN price_comparison_details pcd ON pcd.comparison_id=pc.comparisonid
INNER JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pc.comparisonid AND pcq.product_id=pcd.productid
LEFT JOIN requisition r ON r.REQUISITION_ID=pcq.requisition_id
WHERE CS_ID='$searchId'";
$var = find($sql);

//echo $UserLevelId;
$rec_com = find("select * from price_comparison where comparisonid='$searchId'");
$TaxTypeList = rs2array(query("SELECT TAX_TYPE_ID,TAX_TYPE FROM tax_type"));

$Module = 'Product Approval';

if (isSave()) {
    $footer = getParam('footer');
    $body = getParam('body');
    $subject = getParam('subject');
    $cc = getParam('cc');
    $date = getParam('date');
    $ref = getParam('ref');

    include '../GeneralProcure/manager.php';


    $approvalComment = getParam('comments');


    $manage = new WorkFlowManager();

    if ($UserLevelId == 5) {
        $manage->SendHeadOfProcure($var->CS_ID, $approvalComment);
        $manage->insertWorkFlow($searchId, $employeeId, $Designation, $approvalComment);
    } elseif ($UserLevelId == 6) {
        $manage->SendFinance($var->CS_ID, $approvalComment);
        $manage->insertWorkFlow($searchId, $employeeId, $Designation, $approvalComment);
    } elseif ($UserLevelId == 17) {
        $manage->SendHeadOfPayment($var->CS_ID, $approvalComment);
        $manage->insertWorkFlow($searchId, $employeeId, $Designation, $approvalComment);
    } elseif ($UserLevelId == 25) {
        $manage->SendCFO($var->CS_ID, $approvalComment);
        $manage->insertWorkFlow($searchId, $employeeId, $Designation, $approvalComment);
    } elseif ($UserLevelId == 16) {
        $manage->ApprovalMatrix($searchId, "$Module", "$var->CS_ID", $employeeId, $approvalComment);
        $manage->insertWorkFlow($requisitionId, $employeeId, $Designation, $approvalComment);
    } else {
        $manage->ApprovalMatrix($searchId, "$Module", "$var->CS_ID", $employeeId, $approvalComment);
        $manage->insertWorkFlow($requisitionId, $employeeId, $Designation, $approvalComment);
    }

    echo "<script>location.replace('index.php');</script>";
}
?>

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

<div class="easyui-layout" style="margin: auto; height:1000px;">  
    <div data-options="region:'center'" Title='' style="padding: 10px 10px; background-color:white; "> 

        <img src="../public/images/logo.gif" width="100" height="60"/>
        <div class="float-right">
            <b>City Bank Center</b><br /> 136, Gulshan Avenue, Gulshan-2, <br />
            Dhaka-1212, Bangladesh<br />Web: www.thecitybank.com.bd
        </div>

        <hr>



        <?php echo html_entity_decode($forwarding_text); ?>

        <form action="" method="POST">
            <input type="hidden" name="comparison_id" value="<?php echo $searchId; ?>"/>
            <div>
                <div class="float-left fc">Ref: <?php echo $var->REF; ?></div>
                <div class="float-right fc">Date: <?php echo $var->DATE; ?></div>
            </div><br><br>
            <div>
                PR : <?php echo "<a href='../manage_product/reco_details.php?reco_id=$requisitionId&productid=$pr_rec->productid' target='_blank'>$var->REQUISITION_NO</a><br>"; ?>
            </div><br><br>
            To: <br />
            <?php echo $var->CC; ?><br><br>
            Subject: <br />
            <div class="fc"><?php echo $var->SUBJECT; ?></div><br><br>
            <div><?php echo $var->BODY; ?></div><br>

            <?php
            if ($budgetAllocation > 0) {

                $sl = 1;
                $CcList = $ObjRequisitionData->get_cc_list_by_requisition_id($requisitionId);
                while ($RowCc = fetch_object($CcList)) {
                    $year = date('Y');
                    ?>
                    <h3>Cost Center: <?php echo $RowCc->COST_CENTER_NAME; ?></h3>
                    <table class="ui-state-default"  style="width: 95%;">
                        <thead>
                        <th width = "20">SL</th>
                        <th>Product</th>
                        <th>GL Head</th>
                        <th width = "100">Bill Amount</th>
                        <th width = "150">Tax Info</th>
                        <th width = "50">Tax Amount</th>
                        <th width = "50">Vat Amount</th>
                        <th width = "100">Net Payable</th>
                        <th width="100">Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $sl = 1;
                            $IncomingData = $ObjRequisitionData->GetDataBudgetConfig($requisitionId, $RowCc->REQUISITION_CC_ID);
                            while ($Row = fetch_object($IncomingData)) {
                                $TaxAmount = ($Row->TOTAL * $Row->GlTax) / 100;
                                $VatAmount = ($Row->TOTAL * $Row->GlVat) / 100;
                                $grand_total+=$Row->PAYABLE;
                                ?>
                                <tr>
                                    <td><?php echo $sl; ?>.<input type="hidden" name="AllocationID[<?php echo $Row->BUDGET_ALLOCATION_ID; ?>]" value="<?php echo $Row->BUDGET_ALLOCATION_ID; ?>" /></td>
                                    <td><?php echo $Row->productName; ?></td>
                                    <td><?php echo $Row->GL_ACCOUNT_ID . '->' . $Row->GL_ACCOUNT_NAME; ?></td>
                                    <td><?php echo $Row->TOTAL; ?></td>
                                    <td><?php echo $Row->TAX_TYPE; ?></td>
                                    <td><?php echo $Row->VAT; ?></td>
                                    <td><?php echo $Row->TAX; ?></td>
                                    <td><?php echo $Row->PAYABLE; ?></td>
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
                        <td align="right"><b><?php echo $grand_total; ?></b></td>
                    </tr>
                </table>
                In Word: <b><?php echo convert_number_word($grand_total) . 'Taka Only'; ?></b>

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
                    $supplierListSQL = "SELECT  pc.detailsid, SUPPLIER_NAME, pc.supplier_id, pcq.cs_qty, 
                    pcq.rate, pcq.product_id, sl, p.PRODUCT_NAME, unite_price, requisition_id
                    FROM price_comparison_details pc
                    LEFT JOIN product p ON p.PRODUCT_ID=pc.productid
                    LEFT JOIN supplier sp ON sp.SUPPLIER_ID = pc.supplier_id
                    LEFT JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pc.comparison_id AND pcq.product_id=pc.productid
                    WHERE pc.comparison_id='$searchId' AND selected=1";
                    $resultSupp = query($supplierListSQL);
                    while ($resultObj = fetch_object($resultSupp)) {
                        //$unit_price = findValue("SELECT unite_price FROM price_comparison_details WHERE comparison_id='$searchId' AND productid='$row->productid' AND sl='$resultObj->sl'");

                        $grand_total+=($resultObj->cs_qty * $resultObj->unite_price);
                        ?>
                        <tr style="font-weight: bold;">
                            <td><?php echo++$SL1; ?></td>
                            <td><?php echo $resultObj->PRODUCT_NAME; ?><input type="hidden" name="product[]" class="product" value="<?php echo $resultObj->product_id; ?>" /></td>
                            <td><?php echo $resultObj->SUPPLIER_NAME; ?></td>
                            <td align="right"><?php echo $resultObj->cs_qty; ?></td>
                            <td align="right"><?php echo formatMoney($resultObj->unite_price); ?></td>
                            <td><?php echo formatMoney($resultObj->cs_qty * $resultObj->unite_price); ?></td>
                            <td><button type="button" requisitionId="<?php echo $resultObj->requisition_id; ?>" onclick="addCc($(this));">Add CC</button></td>
                        </tr>
                        <tr>
                            <td colspan="7" style="" class="">
                                <?php
                                $CC = query("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, 
                                        s.SOL_NAME, rcc.CC_AMOUNT, rcc.REQUISITION_CC_LIST_ID
                                        FROM requisition_cc_list rcc
                                        LEFT JOIN cost_center cc ON cc.COST_CENTER_ID=rcc.REQUISITION_CC_ID
                                        LEFT JOIN sol s ON s.SOL_ID=rcc.SOL_ID
                                        WHERE REQUISITION_ID='$resultObj->requisition_id' AND PRODUCT_ID='$resultObj->product_id'");
                                while ($row = mysql_fetch_object($CC)) {
                                    echo "<div id='ccDiv' productCcList='1' class='productCcList' style='height: 15px; border-bottom: 1px dotted gray; text-align: left; padding: 2px 0px;'>
                                            <div class='float-left' style='width: 400px; text-align: left; padding-left:50px;'>$row->COST_CENTER_CODE-$row->COST_CENTER_NAME</div>
                                            <div class='float-left' style='width: 100px; text-align: left; padding-left:10px;'>$row->SOL_NAME</div>
                                            <div class='float-left' style='text-align: left; padding-left:50px;'>" . formatMoney($row->CC_AMOUNT) . "</div>
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
                        <td></td>
                        <td><b>Grand Total: </b></td>
                        <td><b><?php echo formatMoney($grand_total); ?></b></td>                
                    </tr>
                </table>
                <?php if ($UserLevelId == 17) { ?>
                    <a class='button' href='../BudgetAllocation/BudgetAllocationNew.php?search_id=<?php echo $requisitionId; ?>'> GL Allocation </a>
                    <?php
                }
            }
            ?>

            <br/>

            <div name="footer" style="width:100%; height:50px;"><?php echo $var->FOOTER; ?></div><br>

            <?php deligationView("$searchId", "$Module"); ?>


            <?php
            $HistorySQL = "SELECT CONCAT(e.FIRST_NAME, ' ', e.LAST_NAME, '(',CARD_NO,')') AS app_person,
            SL, wm.CREATED_DATE, wm.APPROVAL_COMMENT

            FROM workflow_manager wm
            LEFT JOIN requisition r ON r.REQUISITION_ID=wm.REQUISITION_ID
            LEFT JOIN employee e ON e.EMPLOYEE_ID = wm.CREATED_BY
            WHERE wm.REQUISITION_ID='$search_id' AND APPROVE_STATUS='1'";
            $QueryResult = $db->query($HistorySQL);
            //AND APPROVE_STATUS=1
            ?>

            <h3>Requisition Approval History</h3>
            <table class="ui-state-default">
                <thead>
                <th width="20">SL</th>
                <th width="300">Name</th>
                <th width="100">Date</th>
                <th>Approval Comment</th>
                </thead>
                <tbody>
                    <?php
                    while ($rowQuery = fetch_object($QueryResult)) {
                        ?>
                        <tr>
                            <td><?php echo++$no; ?>.</td>
                            <td><?php echo $rowQuery->app_person; ?></td>
                            <td><?php echo bddate($rowQuery->CREATED_DATE); ?></td>
                            <td><?php echo $rowQuery->APPROVAL_COMMENT; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            if ($rec_com->status < 3) {
                ?>
                <table width="100%">
                    <tr>
                        <td valign="top" width="100">Comments: </td>
                        <td><textarea name="comments" rows="4" cols="48"></textarea></td>
                    </tr>
                </table>
                <input type="submit" name="save" value="Send For Review"/> 
                <a href="../manage_product/evaluation_statement.php?comparison_id=<?php echo $searchId; ?>" target="_blank">View CS</a>
                <?php
            }
            ?>
        </form>

    </div>
</div>

<?php
include '../body/footer.php';
?>
