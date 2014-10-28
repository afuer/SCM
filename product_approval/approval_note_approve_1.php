<?php
include_once '../lib/DbManager.php';
include '../body/header.php';

$searchId = getParam('comparison_id');
$mode = getParam('mode');

$sql = "SELECT APPROVAL_NOTE_ID, CS_ID, REF, ra.DATE, CC, `SUBJECT`, BODY, FOOTER, 
    comparative_code,  ra.`STATUS`, LAST_APPROVAL_ID, ra.PRESENT_LOCATION_ID
    FROM requisition_approval ra
    INNER JOIN price_comparison pc ON pc.comparisonid=ra.CS_ID
    INNER JOIN price_comparison_details pcd ON pcd.comparison_id=pc.comparisonid
    INNER JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pc.comparisonid AND pcq.product_id=pcd.productid
    WHERE CS_ID='$searchId'";
$var = find($sql);


$rec_com = find("select * from price_comparison where comparisonid='$searchId'");

if (isSave()) {
    $footer = getParam('footer');
    $body = getParam('body');
    $subject = getParam('subject');
    $cc = getParam('cc');
    $date = getParam('date');
    $ref = getParam('ref');
    $lastApprovalId = getParam('employeeId');


    $approvalComment = getParam('comments');
    $Module = 'Product Approval';
   

    if ($var->STATUS < 4) {
        echo $insert_sql = "UPDATE requisition_approval SET 
        LAST_APPROVAL_ID='$lastApprovalId', 
        PRESENT_LOCATION_ID='$lineManagerId',
        MODIFY_BY='$employeeId',
        `STATUS`='4',
        MODIFY_DATE=NOW()
        WHERE CS_ID='$searchId'";
        query($insert_sql);
    } else {

        if ($var->LAST_APPROVAL_ID == $var->PRESENT_LOCATION_ID && $var->LAST_APPROVAL_ID != '') {

            $insert_sql = "UPDATE requisition_approval SET 
            PRESENT_LOCATION_ID=null,
            MODIFY_BY='$employeeId',
            `STATUS`='10',
            MODIFY_DATE=NOW()
            WHERE CS_ID='$searchId'";

            query($insert_sql);
            
            sql("UPDATE price_comparison SET approved=3 WHERE comparisonid='$searchId'");
        } else {
            $insert_sql = "UPDATE requisition_approval SET 
            PRESENT_LOCATION_ID='$lineManagerId',
            MODIFY_BY='$employeeId',
            `STATUS`='5',
            MODIFY_DATE=NOW()
            WHERE CS_ID='$searchId'";
            query($insert_sql);
        }
    }

    $workflow_sql = "INSERT INTO workflow_manager (REQUISITION_ID, MODULE_NAME, APPROVAL_COMMENT, LINE_MANAGER_DESIGNATION_ID, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
                    VALUES('$searchId', '$Module', '$approvalComment', '$Designation', 1, '$employeeId', NOW())";

    query($workflow_sql);


    echo "<script>location.replace('index.php');</script>";
}

//$WorkFlowProcessList = getWorkFlowProcessList();
//comboBox('workflow', $WorkFlowProcessList, '', TRUE, 'autoHight');
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
</style>

<script>

    $(document).ready(function() {

        addtr();

    });


    function addtr() {
        var comparativeId = getParam('search_id');

        //var countTr = $('#productGrid tbody tr').length;
        //var sl = countTr + 1;

        var newtr = $('<tr>\n\
            <td valign="top"><select name="workFlowTypeId[]" style="width:100%" class="workFlowTypeId required">' + $('#workflowID').html() + '</select>\n\
            <td valign="top"><input type="text" name="productid[]" style="width:100%" class="productid required" />\n\
            <input type="hidden" name="employeeId[]" class="employeeId" /></td>\n\\n\
            <input type="hidden" name="designationId[]" class="designationId" /></td>\n\\n\
            <td valign="top"><label class="empInfo">q</label></td>\n\
            <td valign="top"><div id="addFlow" searchId="' + comparativeId + '" onClick="SaveEmployeeApprovalNote($(this))">Save</div>\n\
            <div class="remove float-right" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');

        $('.productid', newtr).autocomplete({
            source: 'autocomplate_search_employee.php',
            minLength: 2,
            select: function(evt, ui)
            {
                var itemrow = $(this).closest('tr');
                itemrow.find('.designationId').val(ui.item.designationId);
                itemrow.find('.employeeId').val(ui.item.employeeId);
                itemrow.find('.empInfo').html(ui.item.label);
            }
        });
        $('#productGrid tbody').append(newtr);
    }


    function SaveEmployeeApprovalNote(obj) {

        var itemrow = $(this).closest('tr'),
                designationId = itemrow.find('.designationId').val(),
                employeeId = itemrow.find('.employeeId').val(),
                searchId = $('#addFlow').attr('searchId');




        itemrow.designationId = designationId;
        itemrow.employeeId = employeeId;
        itemrow.searchId = searchId;

        //var jsonstr = JSON.stringify(itemrow);

        $.ajax({
            url: "ajaxCcAddProductWise.php",
            data: "data=" + jsonstr,
            type: "GET",
            contentType: "application/json",
            dataType: "text"
        });
    }


    function DeleteStackHolder(Requisition_id, Module, Mode) {
        var Requisition_id1 = Requisition_id;
        var Module1 = Module;
        var Mode1 = Mode;
        $.ajax({
            type: "GET",
            url: 'stack_holder_delete.php?&mode=delete&search_id=' + Requisition_id1,
            success: function(data) { //alert (data);
                //console.log(data);
                //window.location.href = 'index.php?requisition_id='+ Requisition_id1;
                window.location.href = 'stack_holder_new.php?mode=' + Mode1 + '&module=' + Module1 + '&requisition_id=' + Requisition_id1;

            }
        });

    }

    function EmployeeInfo(obj) {
        var Card_no, result, itemrow;
        Card_no = obj.val();

        itemrow = obj.closest('tr');
        $('#loder').show();
        $.ajax({
            url: "ajax_employee.php?card_no=" + Card_no,
            type: "GET",
            contentType: "application/json",
            dataType: "text",
            success: function(data) {
                result = JSON.parse(data);
                itemrow.find('#employee_details').html(result.empName);
                itemrow.find('#employee_id').val(result.EMPLOYEE_ID);
                itemrow.find('#designationId').val(result.DESIGNATION_ID);
                $('#loder').hide();
            }
        });
    }

    function AddABoq(TableID) {
        var tr = $('#' + TableID + ' tbody>tr:last').clone(true);
        var td = tr.find('td:first');
        var sl = parseInt(td.text());
        td.text(sl + 1 + '.');
        tr.insertAfter('#' + TableID + ' tbody>tr:last').find('input, select').attr('class', 'add').val('');
    }
</script>
<link rel="stylesheet" type="text/css" href="../jquery-ui/css/ui-lightness/jquery-ui-1.8.16.custom.css" />

<script type='text/javascript' src="../public/js/jquery-ui-autocomplete.js"></script>
<script type='text/javascript' src="../public/js/jquery.select-to-autocomplete.min.js"></script>


<div class="easyui-layout" style="width:1100px; margin: auto; height:800px;">  
    <div data-options="region:'center'" Title='Product Approval Note' style="padding: 10px 10px; background-color:white; "> 

        <form action="" method="POST">
            <input type="hidden" name="comparison_id" value="<?php echo $searchId; ?>"/>
            <input type="hidden" name="mode" value="<?php echo $mode; ?>"/>


            <div class="left"><img src="../public/images/PrimeBank.png" width="220" height="60" /></div>
            <hr><br>
            <div>
                <div class="float-left fc">Ref: <?php echo $var->REF; ?></div>
                <div class="float-right fc">Date: <?php echo $var->DATE; ?></div>
            </div>
            <div class="fc"><?php echo $var->CC; ?></div>



            <div class="fc" style="width:100%; height:50px;"><?php echo $var->SUBJECT; ?></div>
            <div style="width:100%; height:100px;"><?php echo $var->BODY; ?></div>
            <br>
            <br>

            <table class="easyui-datagrid">
                <thead>
                    <tr>
                        <th field='1'>SL.</th>
                        <th field='2' width='250'>Product Name</th>
                        <th field='3' width='250'>Selected Supplier</th>
                        <th field='4'>Qty</th>
                        <th field='5'>Rate</th>
                        <th field='6'>Total</th>
                    </tr>
                </thead>

                <?php
                $supplierListSQL = "SELECT  pc.detailsid, SUPPLIER_NAME, pc.supplier_id, pcq.cs_qty, 
                pcq.rate, pcq.product_id, sl, p.PRODUCT_NAME, unite_price
                FROM price_comparison_details pc
                LEFT JOIN product p ON p.PRODUCT_ID=pc.productid
                LEFT JOIN supplier sp ON sp.SUPPLIER_ID = pc.supplier_id
                LEFT JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pc.comparison_id AND pcq.product_id=pc.productid
                WHERE pc.comparison_id='$searchId' AND selected=1";
                $resultSupp = query($supplierListSQL);
                while ($resultObj = fetch_object($resultSupp)) {
                    //$unit_price = findValue("SELECT unite_price FROM price_comparison_details WHERE comparison_id='$searchId' AND productid='$row->productid' AND sl='$resultObj->sl'");

                    $grand_total+=$resultObj->unite_price;
                    ?>
                    <tr>
                        <td><?php echo++$SL1; ?></td>
                        <td><?php echo $resultObj->PRODUCT_NAME; ?></td>
                        <td><?php echo $resultObj->SUPPLIER_NAME; ?></td>
                        <td align="right"><?php echo $resultObj->cs_qty; ?></td>
                        <td align="right"><?php echo formatMoney($resultObj->unite_price); ?></td>
                        <td><?php echo formatMoney($resultObj->cs_qty * $resultObj->unite_price); ?></td>

                    </tr>
                <?php } ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Grand Total</td>
                    <td><?php echo formatMoney($grand_total); ?></td>                
                </tr>
            </table>
            <br/>

            <div name="footer" style="width:100%; height:50px;"><?php echo $var->FOOTER; ?></div>

            <?php
            if ($var->STATUS < 3) {
                ?>


                <table id="deligation" class="ui-state-default" width="100%">
                    <thead>
                    <th width="150">Last Approval ID</th>
                    <th>Employee Details </th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="Card_no" onchange="EmployeeInfo($(this));" />
                                <input type="hidden" name="employeeId" id="employee_id"/>
                                <input type="hidden" name="designationId" id="designationId"/>
                            </td>
                            <td id="employee_details"></td>
                        </tr>
                    </tbody>
                </table>

                <!--
                <?php deligationAdd(); ?>

            <table id="productGrid">
                <thead>
                <th width="200">Approve Type</th>
                <th width="250">Employee Card No</th>
                <th width="450">Employee Details </th>
                <th width="80">Action</th>
                </thead>
                <tbody></tbody>
            </table>
            <button type="button" class="button" title="productTab" onclick="addtr();">Add More</button>
                -->
                <?php
            }
            include 'ApprovalHistory.php';

            if ($var->STATUS < 10) {
                ?>
                <br><hr><br>
                <table class="table">
                    <tr>
                        <td valign="top" width="100">Comments: </td>
                        <td><textarea name="comments" rows="4" cols="48"></textarea></td>
                    </tr>
                </table>
                <input type="submit" name="save" value="Apoprove & Send"/> 
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
