<?php
include '../lib/DbManager.php';
include './manager.php';


$manager = new WorkFlowManager();

$processDept = getParam('processDept');
$search_id = getParam('search_id');

$sql = "SELECT REQUISITION_NO, REQUISITION_DATE, PROCESS_DEPT_ID, SPECIFICATION, 
JUSTIFICATION, REMARK, e.FIRST_NAME, e.CARD_NO, rt.REQUISITION_TYPE_NAME, FREE_TEXT, HELP_DESK
FROM requisition rq
LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID=rq.REQUISITION_TYPE_ID
LEFT JOIN employee e ON e.EMPLOYEE_ID=rq.CREATED_BY
WHERE REQUISITION_ID='$search_id'";

$sql_details = "SELECT REQUISITION_DETAILS_ID, rd.PRODUCT_ID, p.PRODUCT_NAME, rd.QTY, UNIT_PRICE, 
USER_COMMENT, ut.UNIT_TYPE_NAME, p.PRODUCT_CODE

FROM requisition_details rd
LEFT JOIN product p ON p.PRODUCT_ID=rd.PRODUCT_ID
LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID WHERE REQUISITION_ID='$search_id'";


if ($_POST) {

    $approval_comment = getParam('approval_comment');
    $Qty = getParam('Qty');

    foreach ($Qty as $key => $value) {
        $sql = "UPDATE requisition_details SET QTY='$value' WHERE REQUISITION_DETAILS_ID='$key'";
        $db->sql($sql);
    }

    $manager->ProductApproval($search_id, 'requisition', $employeeId, $Designation, "$approval_comment", "$lineManagerId");

    echo "<script>location.replace('index.php');</script>";
}




$var = $db->find($sql);
$resulProduct = $db->query($sql_details);
$processDepName = $db->findValue("SELECT REQUISITION_TYPE_NAME FROM requisition_type WHERE REQUISITION_TYPE_ID='$processDept'");

include("../body/header.php");
?>

<link rel="stylesheet" type="text/css" href="../jquery-ui/jquery-ui-1.8.23.custom_smoothness/css/smoothness/jquery-ui-1.8.23.custom.css">
<script type='text/javascript' src='../jquery-ui/jquery-ui-1.8.23.custom_smoothness/js/jquery-ui-1.8.23.custom.min.js'></script>

<input type="hidden" name="mode" value="<?php echo $mode ?>" />
<input type="hidden" name="search_id" value="<?php echo $search_id ?>" />


<div Title='Requisition Approval' class="easyui-panel" style="height:1000px;" > 


    <div data-options="region:'center'" Title='Requisition'>  
        <table class="table" style="width: 800px;">
            <tr>
                <td width="120">PR No :  </td>
                <td width="200"><?php echo $var->REQUISITION_NO; ?></td >
                <td width="120">Staff Member :</td>
                <td><?php echo $var->FIRST_NAME . '(' . $var->CARD_NO . ')'; ?></td>
            </tr>
            <tr>
                <td>Requisition Date :</td>
                <td><?php echo bddate($var->REQUISITION_DATE); ?></td>
                <td>Location :</td>
                <td><?php echo user_location($var->CARD_NO); ?></td>
            </tr>
            <tr>
                <td>Created by :</td>
                <td><?php echo $var->CARD_NO; ?></td>
                <td>Process Dept : </td>
                <td><?php echo $var->REQUISITION_TYPE_NAME; ?></td>
            </tr>                    
        </table>
        <br/>

        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Add Product</legend>
            <form class="" action="" method="POST">
                <table class="ui-state-default" style="width: 780px;">
                    <thead>
                    <th width="20">SL</th>
                    <th>Product</th>
                    <th width="80">Qty</th>
                    <th width="80">Price</th>
                    <th width="80">Total</th>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        while ($row = fetch_object($resulProduct)) {
                            $grandTotal+=($row->QTY * $row->UNIT_PRICE);
                            $total = ($row->QTY * $row->UNIT_PRICE) > 0 ? round($row->QTY * $row->UNIT_PRICE, 2) : 'N/A';
                            ?>
                            <tr>
                                <td><?php echo $sl; ?></td>
                                <td><?php echo $row->PRODUCT_NAME; ?></td>
                                <td align='center'><input type="text" name="Qty[<?php echo $row->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $row->QTY; ?>"/></td>
                                <td align='right'><?php echo $row->UNIT_PRICE > 0 ? $row->UNIT_PRICE : 'N/A'; ?></td>
                                <td id="TotalPrice" align='right'><?php echo $total; ?></td>
                            </tr>
                            <?php
                            $sl++;
                        }
                        $grandTotal = $grandTotal > 0 ? formatMoney($grandTotal) : 'N/A';
                        ?>
                    </tbody>
                    <tfoot>
                    <th></th>
                    <th colspan="3" align="right">Grand Total</th>
                    <th align="right"><div id="ProductGrantTotal"><?php echo $grandTotal; ?></div></th>
                    </tfoot>
                </table>
        </fieldset>
        <br/>
        <?php file_upload_view("$search_id", "requisition"); ?>
        <br/>
        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Comments</legend>
            <table class="table">
                <tr>
                    <td valign='top' width="150">Specification:</td>
                    <td><?php echo $var->SPECIFICATION; ?></td>
                </tr>
                <tr>
                    <td valign='top'>Justification:</td>
                    <td><?php echo $var->JUSTIFICATION; ?></td> 
                </tr>
                <tr>
                    <td valign='top'>Remark:</td>
                    <td><?php echo $var->REMARK; ?></td>
                </tr>          
                <tr>
                    <td valign='top'>On Behalf Off :</td>
                    <td><?php echo $var->FREE_TEXT; ?></td>
                </tr>          
                <tr>
                    <td valign='top'>Help Desk:</td>
                    <td><?php echo $var->HELP_DESK; ?></td>
                </tr>          
            </table>
        </fieldset>
        <br/>

        <?php include './ApprovalHistory.php'; ?>
        <br/>

        <table>
            <tr>
                <td valign="top" >Comments:</td>
                <td colspan="3"><textarea name="approval_comment" placeholder="Write your approval comment.."></textarea></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <a  href="index.php" class="button">Back</a>
        <button type="submit" class="button" name="submit">Submit</button>
        </form>
    </div>
</div>


<?php include '../body/footer.php'; ?>
