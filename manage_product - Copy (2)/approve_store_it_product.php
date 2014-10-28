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





if (!empty($approved)) {
    $orderids = getParam("orderids");
    $delivery_qty = getParam("delivery_qty");
    $orderid = getParam('orderid');
    $date = date('Y-m-d');
    $ref_date = getParam('ref_date');
    $bill_no = getParam('bill_no');
    $supplier_id = getParam('supplier_id');
    $sol = getParam('sol');
    $costCenter = getParam('costCenter');
    $unitPrice = getParam('unitPrice');

    $sql = "insert into it_store_product_approval(APPROVAL_DATE, CREATED_BY, CREATED_DATE)
		values(NOW(), '$employeeId', NOW())";
    sql($sql);
    $lastId = insert_id();


    foreach ($orderids as $key => $value) {

        $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];

        $sql_details = "update requisition_details set
            DELIVERED_QTY=IFNULL(DELIVERED_QTY,0)+$deliver_qty,
            DETAILS_STATUS=15, 
            STATUS_APP_LEVEL=3,
            IT_APPROVAL_ID='$lastId'
            WHERE REQUISITION_DETAILS_ID='$key'";
        $db->sql($sql_details);
    }
    echo "<script>location.replace('approve_store_it_product.php');</script>";
}

$sqlMain = "SELECT si.PRODUCT_ID,
        si.REQUISITION_ID, si.REQUISITION_DETAILS_ID,
        pr.PRODUCT_NAME, 
        si.QTY as quantities,
        si.UNIT_PRICE,
        APPROVE_QTY, si.DETAILS_STATUS 
               

        FROM requisition_details si
        left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                        
        WHERE pr.PROCESS_DEPT_ID='$ProcessDeptId'
        AND si.DETAILS_STATUS=15 AND si.STATUS_APP_LEVEL=2
        GROUP BY si.PRODUCT_ID";


//AND dh.delivery_qty IS NULL

$sql_result = query($sqlMain);
?>
<div class="panel-header">Waiting For Approve</div>  
<div style="background-color:white; padding: 20px 20px; "> 

    <form name="frm" action="" method='POST' autocomplete="off" class="formValidate">
        <table class="ui-state-default">
            <thead>
            <th width="20">SL.</th>
            <th width="50">Chk</th>
            <th width="100">Requisition No</th>
            <th>Req.Person</th>
            <th>Branch/Dept</th>
            <th width="200">Product Name</th>

            <th>Supplier Name</th>
            <th width="80">Ref. Date</th>
            <th>Bill No</th>
            <th>Cost Center(Sol)</th>
            <th width="50">Delivery Qty </th>
            <th width="50">Unit Price </th>
            <th width="50">Total Price </th>
            </thead>
            <tbody>

                <?php
                while ($rec = fetch_object($sql_result)) {

                    echo "<tr><td></td><td colspan='13' align='left'><b>$rec->PRODUCT_NAME</b></td></tr>";
                    $result = getRequisitionByProduct($ProcessDeptId, "$rec->PRODUCT_ID");

                    while ($row = mysql_fetch_object($result)) {
                        ?>

                        <tr class="datagrid-row">
                            <td><?php echo++$ser; ?>.</td>
                            <td align="center"><input type="checkbox" name="orderids[<?php echo $row->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $row->PRODUCT_ID; ?>" /></td>
                            <td align="left"><a href='reco_details.php?reco_id=<?php printf($row->REQUISITION_ID); ?>' target="_blank"> <?php echo $row->REQUISITION_NO; ?></a></td> 
                            <td><?php echo $row->FIRST_NAME . ' ' . $row->LAST_NAME . ' (' . $row->CARD_NO . ')'; ?></td>
                            <td><?php echo $row->OFFICE_NAME . ' ' . $row->BRANCH_DEPT_NAME; ?></td>
                            <td><?php echo $row->PRODUCT_NAME; ?></td>
                            <td><?php echo $row->SUPPLIER_NAME; ?></td>
                            <td><?php echo $row->REF_DATE; ?></td>
                            <td><?php echo $row->BILL_NO; ?></td>
                            <td><?php echo $row->COST_CENTER_NAME . '(' . $row->SOL_NAME . ')'; ?></td>
                            <td><?php echo $row->APPROVE_QTY; ?></td>
                            <td><?php echo formatMoney($row->UNIT_PRICE); ?></td>
                            <td><?php echo formatMoney($row->APPROVE_QTY * $row->UNIT_PRICE); ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <input type="submit" class="button" value='Submit' name='approved' id="approved" />


    </form>
</div>  

<?php include '../body/footer.php'; ?>