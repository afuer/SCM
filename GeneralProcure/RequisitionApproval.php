<?php
include '../lib/DbManager.php';
include_once 'manager.php';

include("../body/header.php");

$SearchId = getParam('search_id');
$flow_list_id = getParam('flow_list_id');
$mode = getParam('mode');
$buttonName = getParam('Comment_Update');
$ButtonReassign = getParam('reassign');
$ButtonReturn = getParam('return');


$BuAlloConfigCheck = findValue("SELECT COUNT(*) FROM budget_allocation WHERE REQUISITION_ID = '$SearchId'");

$ConfigCheck = findValue("SELECT COUNT(*) FROM budget_allocation WHERE REQUISITION_ID = '$SearchId' AND TAX_TYPE_ID > 0");

$WorkFlowProcessTypeList = rs2array(query("SELECT WORKFLOW_PROCESS_ID,WORKFLOW_PROCESS_NAME  FROM workflow_process_type"));
$WorkFlowProcessId = findValue("SELECT WORKFLOW_PROCESS_TYPE_ID FROM requisition_flow_list WHERE GP_REQUISITION_FLOW_LIST_ID = '$flow_list_id'");

$check_approve = findValue("SELECT COUNT(*) 
FROM requisition_flow_list
WHERE REQUISITION_ID='$SearchId' AND WORKFLOW_PROCESS_TYPE_ID < (
SELECT WORKFLOW_PROCESS_TYPE_ID FROM requisition_flow_list pt 
WHERE pt.GP_REQUISITION_FLOW_LIST_ID='$flow_list_id'
) AND APPROVE_STATUS='0'");




$ResultProduct = query("SELECT rd.REQUISITION_DETAILS_ID,p.PRODUCT_ID,p.PRODUCT_NAME AS PRODUCT_NAME, 
        u.UNIT_TYPE_NAME,rd.UNIT_PRICE,rd.QTY,rd.USER_COMMENT
        FROM requisition AS r
        LEFT JOIN requisition_details AS rd ON r.REQUISITION_ID = rd.REQUISITION_ID
        LEFT JOIN product As p ON p.PRODUCT_ID =rd.PRODUCT_ID 
        LEFT JOIN unit_type AS u ON u.UNIT_TYPE_ID =p.unit_type_ID
        WHERE r.REQUISITION_ID = '$SearchId'");



$SqlCostCode = "SELECT rc.REQUISITION_CC_LIST_ID,rc.CC_AMOUNT,rc.CC_PERCENT, rc.REQUISITION_CC_ID,rc.BUDGET, 
CONCAT(cc.cost_center_code,' - ',cc.cost_center_name) AS 'CcAccount'
FROM requesition AS rm
LEFT JOIN requisition_cc_list AS rc ON rm.REQUISITION_ID = rc.REQUISITION_ID
LEFT JOIN cost_center AS cc ON rc.REQUISITION_CC_ID = cc.cost_center_id
WHERE rm.REQUISITION_ID ='$SearchId'";

$ResultCostCode = query($SqlCostCode);




$SqlWork = "SELECT rf.GP_REQUISITION_FLOW_LIST_ID, rf.EMPLOYEE_ID, rf.DESIGNATION_ID, 
ed.FULL_NAME, wt.WORKFLOW_TYPE_NAME, d.DESIGNATION_NAME, wpt.WORKFLOW_PROCESS_NAME
FROM requisition AS rm
LEFT JOIN requisition_flow_list AS rf ON rm.REQUISITION_ID = rf.REQUISITION_ID 
LEFT JOIN employee As ed On ed.EMPLOYEE_ID = rf.EMPLOYEE_ID
LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID = rm.WORKFLOW_TYPE_ID
LEFT JOIN designation AS d ON d.DESIGNATION_ID =ed.DESIGNATION_ID
LEFT JOIN workflow_process_type AS wpt ON wpt.WORKFLOW_PROCESS_ID=rf.WORKFLOW_PROCESS_TYPE_ID
WHERE rm.REQUISITION_ID ='$SearchId' ORDER BY wpt.WORKFLOW_PROCESS_ID ";
$ResultWorkFlow = query($SqlWork);

$sql_main = "SELECT  r.REQUISITION_NO, r.REQUISITION_DATE, e.CARD_NO, r.SPECIFICATION, 
        r.JUSTIFICATION, r.REMARK, r.CREATED_BY, r.APPROVE_FILE_PATH, 
        r.APPROVE_FILE_TYPE, amt.AMOUNT_TYPE_NAME, 
        et.EXPENSE_TYPE_ID, et.EXPENSE_TYPE_NAME, r.WORKFLOW_TYPE_ID,
        wt.WORKFLOW_TYPE_NAME, WORKFLOW_NAME, REQUISITION_TYPE_ID, PROCESS_DEPT_ID,
		r.REQUISITION_STATUS_ID
        
        #rr.priority_NAME, rm.PRIORITY_ID,rm.REQUISITION_NO, rm.REQUISITION_DATE, rm.CREATED_BY,
        #e.CARDNO, amt.AMOUNT_TYPE_ID, rm.BUDGET, et.EXPENSE_TYPE_ID, et.EXPENSE_TYPE_NAME, 
        #wt.WORKFLOW_TYPE_NAME, rm.MANAGEMENT_APPROVE_FILE, rm.BOARD_APPROVE_FILE,
        #rm.SPECIFICATION, rm.JUSTIFICATION, rm.REMARK, e.FULL_NAME, rm.WORKFLOW_TYPE_ID,
        #AMOUNT_TYPE_NAME, workflow_name, HELP_DESK_NO,rr.PRIORITY_NAME,rm.PRIORITY_ID,
        #rm.REQUISITION_STATUS_ID

        FROM requisition AS r
        LEFT JOIN employee e ON e.EMPLOYEE_ID=r.CREATED_BY
        LEFT JOIN expense_type AS et ON et.EXPENSE_TYPE_ID=r.EXPENSE_TYPE_ID
        LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID=r.WORKFLOW_TYPE_ID
        LEFT JOIN amount_type AS amt ON amt.AMOUNT_TYPE_ID=r.AMOUNT_TYPE_ID 
        #LEFT JOIN employee_details AS e ON e.CARDNO=rm.CREATED_BY
        LEFT JOIN workflow_group AS wg ON wg.workflow_group_id=r.WORKFLOW_GROUP_ID
        #LEFT JOIN priority AS rr ON rr.PRIORITY_ID = rm.PRIORITY_ID
        WHERE r.REQUISITION_ID='$SearchId'";

$ResultRequisitionMain = find($sql_main);


$SqlAttachment = "SELECT FILE_ATTACH_LIST_ID, ATTACH_TITTLE, ATTACH_FILE_PATH
FROM file_attach_list
WHERE REQUEST_ID = '$SearchId' AND MODULE_NAME='Requisition'";
$ResultAttachment = query($SqlAttachment);


if (isSave()) {

    $manager = new WorkFlowManager();
    $approval_comment = getParam('approval_comment');


    if ($UserLevelId == '5' && $ResultRequisitionMain->PROCESS_DEPT_ID == 5) {
        $manager->SendManageProduct($SearchId, 'requisition', $employeeId, $Designation, $approval_comment);
    } else {
        $manager->ProductApproval($SearchId, 'requisition', $employeeId, $Designation, "$approval_comment", $lineManagerId);
    }

    echo "<script>location.replace('RequisitionPendingList.php');</script>";
}
?>

<script type="text/javascript" src="../public/js/jquery.calculation.js"></script>
<script src="Requisition.js"></script>       

<script type="text/javascript">

    function requisitionDetailPriceUpdate(obj) {

        var itemrow = obj.closest('tr'),
                reqDetailId = itemrow.find("input[name='reqDetailId']").attr('reqDetailId'),
                price = itemrow.find("input[name='reqDetailId']").val();
        $.get("ajaxRequisitionDetailsPriceUpdate.php", {reqDetailId: reqDetailId, price: price});
    }



</script>


<div Title='Requisition List' class="easyui-panel" style="height:1000px;" > 

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
                <td><?php //echo $ResultRequisitionMain->PRIORITY_NAME;          ?></td>
            </tr>                    
        </table>
    </fieldset>

    <br/>


    <fieldset class="fieldset"> 
        <legend>Requisition Product List</legend>
        <table class="ui-state-default" style="width: 800px;">
            <thead>
            <th width="20">SL.</th>
            <th>Product</th>
            <th width="100">Qty</th>
            <th width="100">Price</th>
            <th width="100">Total</th>
            <th width="150">Remark</th>
            </thead>
            <tbody>
                <?php
                $j = 1;
                $index = 0;
                $total = 0;
                while ($row = fetch_object($ResultProduct)) {
                    $sum = $row->UNIT_PRICE * $row->QTY;
                    $editPrice = $ResultRequisitionMain->REQUISITION_STATUS_ID == 3 ? : $row->UNIT_PRICE;
                    ?>
                                                                            <!-- <input type='text' name='reqDetailId' class='reqDetailId' reqDetailId='<?php echo $row->REQUISITION_DETAILS_ID; ?>' value="<?php echo $row->UNIT_PRICE; ?>" onkeyup="requisitionDetailPriceUpdate($(this));"/> -->
                    <tr style="font-weight: bold;">
                        <td><?php echo $j; ?>.</td>
                        <td><?php echo $row->PRODUCT_NAME; ?></td>  
                        <td align="center"><?php echo $row->QTY . ' ' . $row->UNIT_TYPE_NAME; ?></td>
                        <td align="right"><?php echo $row->UNIT_PRICE; ?></td>
                        <td align="right"> <?php echo formatMoney($sum); ?> </td>      
                        <td align="center" ><?php echo $row->PRODUCT_REMARK; ?></td>
                    <tr>
                        <td colspan="7" style="" class="">
                            <?php
                            $CC = query("SELECT cc.COST_CENTER_NAME, s.SOL_NAME, rcc.CC_PERCENT 
                            FROM requisition_cc_list rcc
                            LEFT JOIN cost_center cc ON cc.COST_CENTER_ID=rcc.REQUISITION_CC_ID
                            LEFT JOIN sol s ON s.SOL_ID=rcc.SOL_ID
                            WHERE REQUISITION_ID='$SearchId' AND PRODUCT_ID='$row->PRODUCT_ID'");
                            while ($row = mysql_fetch_object($CC)) {
                                echo "<div style='height: 15px; border-bottom: 1px dotted gray; text-align: left; padding: 2px 0px;'>
                                    <div class='float-left' style='width: 450px; text-align: left; padding-left:50px;'>$row->COST_CENTER_NAME</div>
                                    <div class='float-left' style='width: 100px; text-align: left; padding-left:10px;'>$row->SOL_NAME</div>
                                    <div class='float-left' style='text-align: left; padding-left:50px;'>" . formatMoney($row->CC_PERCENT) . "%</div>
                                    </div>";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $j++;
                    $index++;

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
            </tfoot>
        </table>
    </fieldset >
    <br/>
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

    <br/><?php file_upload_view($SearchId, 'requisition', TRUE); ?><br/>

    <fieldset class="fieldset">
        <legend>Comments</legend>
        <table>
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
        </table>

    </fieldset>
    <br/>
    <?php include 'ApprovalHistory.php'; ?>
    <br/>

    <form action="" method="POST">
        <input type='hidden' value='<?php echo $SearchId; ?>' name='search_id' id="search_id"/>
        <table>
            <tr>
                <td valign="top">Comments: </td>
                <td><textarea style="width: 700px" name="approval_comment" placeholder="Write your approval comment.."></textarea></td>
            </tr>
        </table>
        <hr>
        <button type='submit' name='save' value='ApproveRequisition' class='button'>Approve</button>
        <button type = "submit" name = "save" value = "CancelRequisition" class = "button">Cancel</button>

    </form>



</div>

<?php include '../body/footer.php'; ?>
