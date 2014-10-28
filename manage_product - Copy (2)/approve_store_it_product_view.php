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
    $maxId = NextId("it_store_product_approval", "IT_APPROVAL_ID");
    $OrderNo = OrderNo($maxId);

    $sql = "insert into it_store_product_approval(APPROVE_NO, APPROVAL_DATE, CREATED_BY, CREATED_DATE)
		values('$OrderNo', NOW(), '$employeeId', NOW())";
    sql($sql);
    $lastId = insert_id();


    foreach ($orderids as $key => $value) {

        $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];

        $sql_details = "update requisition_details set
            DELIVERED_QTY=IFNULL(DELIVERED_QTY,0)+$deliver_qty,
            DETAILS_STATUS=15, 
            STATUS_APP_LEVEL=2,
            IT_APPROVAL_ID='$lastId'
            WHERE REQUISITION_DETAILS_ID='$key'";
        $db->sql($sql_details);
    }
    echo "<script>location.replace('approve_store_product_for_gatepass.php');</script>";
}

$sql_produc_list = "SELECT si.PRODUCT_ID,
        si.REQUISITION_ID, si.REQUISITION_DETAILS_ID,
        pr.PRODUCT_NAME, 
        si.QTY as quantities,
        si.UNIT_PRICE,
        so.CREATED_BY,
        dv.DIVISION_NAME, 
        so.OFFICE_TYPE_ID, 
        so.BRANCH_DEPT_ID,
        so.REQUISITION_NO,
        e.FIRST_NAME, e.LAST_NAME,
        e.CARD_NO, ot.OFFICE_NAME, bd.BRANCH_DEPT_NAME,
        APPROVE_QTY, si.DETAILS_STATUS, e.COST_CENTER_ID, bd.SOL_ID,
        cos.COST_CENTER_NAME, s.SOL_NAME, sup.SUPPLIER_NAME, si.REF_DATE, si.BILL_NO
        

        FROM requisition_details si
        LEFT JOIN supplier sup ON sup.SUPPLIER_ID=si.SUPPLIER_ID
        LEFT JOIN cost_center cos ON cos.COST_CENTER_ID=si.COST_CENTER_ID
        left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
        left join requisition so on si.REQUISITION_ID=so.REQUISITION_ID
        left join division dv on dv.DIVISION_ID=so.DIVISION_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        LEFT JOIN sol s ON s.SOL_ID=bd.SOL_ID
        
        WHERE so.REQUISITION_STATUS_ID=3 AND pr.PROCESS_DEPT_ID='$ProcessDeptId'
        AND si.DETAILS_STATUS=14 AND si.STATUS_APP_LEVEL=2
        ORDER BY so.REQUISITION_ID DESC";
//AND dh.delivery_qty IS NULL

$sql = query($sql_produc_list);
?>
<div class="panel-header">Waiting Confirm</div>  
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
                while ($rec = fetch_object($sql)) {
                    $totall++;
                    ?>

                    <tr class="datagrid-row">
                        <td><?php echo $totall; ?>.</td>
                        <td align="center"><input type="checkbox" name="orderids[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>" checked="true" /></td>
                        <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                            <input type='hidden' name='costCenter[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->COST_CENTER_ID; ?>' />
                            <input type='hidden' name='orderid[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' />
                        </td> 
                        <td><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . ' (' . $rec->CARD_NO . ')'; ?></td>
                        <td><?php echo $rec->OFFICE_NAME . ' ' . $rec->BRANCH_DEPT_NAME; ?></td>
                        <td><?php echo $rec->PRODUCT_NAME; ?></td>
                        <td><?php echo $rec->SUPPLIER_NAME; ?></td>
                        <td><?php echo $rec->REF_DATE; ?></td>
                        <td><?php echo $rec->BILL_NO; ?></td>
                        <td><?php echo $rec->COST_CENTER_NAME . '(' . $rec->SOL_NAME . ')'; ?></td>
                        <td><?php echo $rec->APPROVE_QTY; ?></td>
                        <td><?php echo formatMoney($rec->UNIT_PRICE); ?></td>
                        <td><?php echo formatMoney($rec->APPROVE_QTY * $rec->UNIT_PRICE); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <input type="submit" class="button" value='Confirm' name='approved' id="approved" />


    </form>
</div>  

<?php include '../body/footer.php'; ?>