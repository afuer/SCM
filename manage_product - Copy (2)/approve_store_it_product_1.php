<?php
include '../lib/DbManager.php';
include "../body/header.php";



$costCenterList = $db->rs2array('SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME');
$supplier_list = $db->rs2array("SELECT s.SUPPLIER_ID, s.SUPPLIER_NAME
FROM supplier_price sp
INNER JOIN supplier s ON s.SUPPLIER_ID=sp.SUPPLIER_ID
GROUP BY s.SUPPLIER_ID");
$solList = rs2array(query("SELECT SOL_ID, SOL_CODE, SOL_NAME FROM sol ORDER BY SOL_NAME"));





$req_id = getParam('req_id');
$approved = getParam('approved');
$details_status = getParam('details_status');
$approval_status = getParam('approval_status');



$productname = findValue("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$productid'");



if (isSave()) {

    $orderids = getParam("orderids");
    $delivery_qty = getParam("delivery_qty");


    foreach ($orderids as $key => $value) {

        $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];

        $sql_details = "update it_store_product_approval set
            APPROVAL_STATUS=10
            WHERE IT_APPROVAL_ID='$orderids[$key]'";
        $db->sql($sql_details);
    }
    echo "<script>location.replace('approve_store_it_product_1.php');</script>";
}


$sql_produc_list = "SELECT spa.IT_APPROVAL_ID, SUM(IFNULL(DELIVERED_QTY,0)*IFNULL(UNIT_PRICE,0)) AS total, spa.APPROVE_NO,
CONCAT(e.FIRST_NAME,' ',e.LAST_NAME,'->', e.CARD_NO) AS empName, DATE(spa.APPROVAL_DATE) AS APPROVAL_DATE
FROM it_store_product_approval spa
INNER JOIN employee AS e ON e.EMPLOYEE_ID=spa.CREATED_BY
INNER JOIN requisition_details rd ON rd.IT_APPROVAL_ID=spa.IT_APPROVAL_ID
WHERE spa.APPROVAL_STATUS=0
GROUP BY spa.IT_APPROVAL_ID ORDER BY spa.IT_APPROVAL_ID DESC";

$sql = query($sql_produc_list);

$sql_result = query($sqlMain);
?>
<div class="panel-header">Waiting For Approve</div>  
<div style="background-color:white; padding: 20px 20px; "> 


    <form name="frm" action="" method='POST' autocomplete="off" class="formValidate">
        <table class="ui-state-default">
            <thead>
            <th width="50">Chk</th>
            <th width="20">SL.</th>

            <th width="20">Approval No</th>
            <th width="100">Date</th>
            <th>Req.Person</th>
            <th width="50">Price </th>
            <th width="50">Action</th>
            </thead>
            <tbody>

                <?php
                while ($rec = fetch_object($sql)) {
                    ?>

                    <tr class="datagrid-row">
                        <td align="center"><input type="checkbox" name="orderids[<?php echo $rec->IT_APPROVAL_ID; ?>]" value="<?php echo $rec->IT_APPROVAL_ID; ?>" /></td>
                        <td><?php echo++$sl; ?>.</td>
                        <td><?php echo $rec->APPROVE_NO; ?></td> 
                        <td align="left"><?php echo $rec->APPROVAL_DATE; ?></td> 
                        <td><?php echo $rec->empName; ?></td>
                        <td align='right'><?php echo formatMoney($rec->total); ?></td>
                        <td align="left"><a href='approve_store_it_product_list_details.php?itApprovalId=<?php echo $rec->IT_APPROVAL_ID; ?>' target="_blank"> View</a></td> 
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <input type="submit" class="button" value='Submit' name='save' />


    </form>


</div>  

<?php include '../body/footer.php'; ?>