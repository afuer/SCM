<?php
include '../lib/DbManager.php';

include "../body/header.php";



$req_id = getParam('req_id');
$send = getParam('send');
$approved = getParam('approved');
$details_status = getParam('details_status');
$approval_status = getParam('approval_status');
$productname = findValue("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$productid'");

$branchDeptLis = rs2array(query("SELECT BRANCH_DEPT_ID, BRANCH_DEPT_CODE, BRANCH_DEPT_NAME 
FROM branch_dept 
ORDER BY BRANCH_DEPT_NAME"));

if ($send == 'Send To Line Manager') {

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
    $branchDeptId = getParam('branchDeptId');

    foreach ($orderids as $key => $value) {
        $sql = "insert into app_product_delivery_history (req_id, product_id, delivery_qty, delivered_by, delivery_date)
		values('$orderid[$key]', '$value', '$delivery_qty[$key]', '$employeeId', '$date')";
        sql($sql);

        $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];

        $req = find("SELECT REQUISITION_ID
        FROM requisition_details
        WHERE REQUISITION_DETAILS_ID = '$key'");

        sql("UPDATE requisition SET BRANCH_DEPT_ID='$branchDeptId[$key]' WHERE REQUISITION_ID='$req->REQUISITION_ID'");

        $var = find("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_ID, cc.COST_CENTER_NAME, s.SOL_CODE, s.SOL_NAME
        FROM branch_dept bd
        LEFT JOIN sol s ON s.SOL_ID=bd.SOL_ID
        LEFT JOIN cost_center cc ON cc.DIVISION_ID=s.DIVISION_ID

        WHERE bd.BRANCH_DEPT_ID='$val'");


        $sql_details = "update requisition_details set
                DELIVERED_QTY=IFNULL(DELIVERED_QTY,0)+$deliver_qty,
                APPROVE_QTY=$deliver_qty,
                DETAILS_STATUS=2, 
                STATUS_APP_LEVEL=1,
                REF_DATE='$ref_date[$key]', 
                BILL_NO='$bill_no[$key]', 
                UNIT_PRICE='$unitPrice[$key]',
                COST_CENTER_ID='$var->COST_CENTER_ID', 
                SOL_ID='$var->SOL_ID', 
                SUPPLIER_ID='$supplier_id[$key]'
                WHERE REQUISITION_DETAILS_ID='$key'";
        $db->sql($sql_details);
    }
    echo "<script>location.replace('approve_store_it_product_view.php');</script>";
}
?>



<script type="text/javascript">


    function getCcSol(obj) {
//        $.ajax({
//            url: 'ajax_cc_sol_by_branchId.php',
//            type: "GET",
//            data: 'branchDeptId=' + obj.val(),
//            success: function(msg) {
//                obj.closest('td').next().html(msg);
//            }
//        });
    }

</script>

<div class="panel-header">Requisition Details List</div>  
<div style="background-color:white; "> 

    <?php if ($ProcessDeptId == 2) { ?>

        <form name="frm" action="" method='POST' autocomplete="off" class="formValidate">

            <h3 align='center'>DSC Requisition Details</h3>
            <table class="ui-state-default">
                <thead>
                <th width="20">SL.</th>
                <th width="50">Chk</th>
                <th width="100">Requisition No</th>
                <th>Req.Person</th>
                <th>Branch/Dept</th>
                <th width="200">Product Name</th>

                <th width="100">Stock Qty</th>
                <th width="80">Allocated Qty</th>
                <th width="60">Available Qty</th>

                <th width="50">Req.Qty</th>
                <th width="50">Delivery Qty </th>
                </thead>
                <tbody>

                    <?php
                    $sql_produc_list = "SELECT si.PRODUCT_ID,
                        si.REQUISITION_ID, si.REQUISITION_DETAILS_ID,
                        pr.PRODUCT_NAME, 
                        sum(si.QTY) as quantities,
                        so.CREATED_BY,
                        dv.DIVISION_NAME, 
                        so.OFFICE_TYPE_ID, 
                        so.BRANCH_DEPT_ID,
                        so.REQUISITION_NO,
                        e.FIRST_NAME, e.LAST_NAME,
                        e.CARD_NO, ot.OFFICE_NAME, bd.BRANCH_DEPT_NAME,
                        SUM(IFNULL(si.DELIVERED_QTY,0)) AS DELIVERED_QTY,
                        dh.deliverd,
                        sm.stock,
                        (
                            SELECT sum(delivery_qty)
                            FROM app_product_delivery_history
                            WHERE product_id=si.PRODUCT_ID AND challan_id IS NULL GROUP BY product_id
                        ) AS allocated,
                        '' AS 'availableqty'

                        FROM requisition_details si
                        left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                        left join requisition so on si.REQUISITION_ID=so.REQUISITION_ID
                        left join (
                                select req_id, product_id, sum(delivery_qty) as deliverd
                                from app_product_delivery_history
                                group by req_id, product_id
                                ) dh on si.REQUISITION_ID=dh.req_id and si.PRODUCT_ID=dh.product_id
                        left join (
                                select PRODUCT_ID, sum(QTY) as stock
                                from stockmove
                                group by PRODUCT_ID
                        ) sm on si.PRODUCT_ID=sm.PRODUCT_ID

                        left join division dv on dv.DIVISION_ID=so.DIVISION_ID
                        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
                        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
                        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID

                        WHERE so.REQUISITION_ID='$req_id' AND si.DETAILS_STATUS=1 AND  si.PROCESS_DEPT_ID='2'
                        GROUP BY si.PRODUCT_ID";
                    $sql = query($sql_produc_list);

                    while ($rec = fetch_object($sql)) {
                        $totall++;

                        $pending_pre = $rec->quantities - $rec->deliverd;
                        $available = $rec->stock - $rec->allocated;
                        ?>

                        <tr class="datagrid-row">
                            <td><?php echo $totall; ?>.</td>
                            <td align="center"><input type="checkbox" name="orderids[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>" /></td>
                            <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                                <input type='hidden' name='orderid[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' /></td> 
                            <td><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . ' (' . $rec->CARD_NO . ')'; ?></td>
                            <td><?php echo $rec->OFFICE_NAME . ' ' . $rec->BRANCH_DEPT_NAME; ?></td>
                            <td><?php echo $rec->PRODUCT_NAME; ?></td>

                            <td align="center"><?php echo $rec->stock; ?></td>
                            <td align="center"><?php echo $rec->allocated; ?></td>
                            <td align="center"><?php echo $available; ?></td>

                            <td align="center"><?php echo $rec->quantities; ?></td>
                            <td align="center"><input type="text" name="delivery_qty[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" size="10" value="<?php echo $rec->quantities - $rec->DELIVERED_QTY; ?>" min="0" max="<?php echo $rec->quantities - $rec->DELIVERED_QTY; ?>"/>
                                <input type='hidden' name='pending_pre[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $pending_pre; ?>' />
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <p
                <input type="hidden" name="productid" value="<?php echo $productid; ?>" />
                <input type="hidden" name="count" value="<?php echo $totall; ?>" />
                <input type="submit" class="button" value='Send to Create Challan' name='approved' id="approved" />
            </p>

        </form>

        <?php
    } else {

        $costCenterList = $db->rs2array('SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME');
        $supplier_list = $db->rs2array("SELECT s.SUPPLIER_ID, s.SUPPLIER_NAME
        FROM supplier_price sp
        INNER JOIN supplier s ON s.SUPPLIER_ID=sp.SUPPLIER_ID
        GROUP BY s.SUPPLIER_ID");
        $solList = rs2array(query("SELECT SOL_ID, SOL_CODE, SOL_NAME FROM sol ORDER BY SOL_NAME"));

        $sql_produc_list = "SELECT si.PRODUCT_ID,
        si.REQUISITION_ID, si.REQUISITION_DETAILS_ID,
        pr.PRODUCT_NAME, si.QTY,
        si.QTY as quantities,
        so.CREATED_BY,
        dv.DIVISION_NAME, 
        so.OFFICE_TYPE_ID, 
        so.BRANCH_DEPT_ID,
        so.REQUISITION_NO,
        e.FIRST_NAME, e.LAST_NAME,
        e.CARD_NO, ot.OFFICE_NAME, bd.BRANCH_DEPT_NAME,
        APPROVE_QTY, si.DETAILS_STATUS, e.COST_CENTER_ID, bd.SOL_ID,
        cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, so.FREE_TEXT
        

        FROM requisition_details si
        left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
        left join requisition so on si.REQUISITION_ID=so.REQUISITION_ID
        left join division dv on dv.DIVISION_ID=so.DIVISION_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        LEFT JOIN sol s ON s.SOL_ID=bd.SOL_ID
        LEFT JOIN cost_center cc ON cc.DIVISION_ID=s.DIVISION_ID
        
        WHERE so.REQUISITION_STATUS_ID=3 AND pr.PROCESS_DEPT_ID='$ProcessDeptId'
        AND si.DETAILS_STATUS=1 AND si.REQUISITION_ID='$req_id'
        ORDER BY so.REQUISITION_ID DESC";
        $sql = query($sql_produc_list);
        ?>

        <div style="background-color:white; padding: 20px 20px; "> 

            <form name="frm" action="" method='POST' autocomplete="off" class="formValidate">
                <table class="ui-state-default">
                    <thead>
                    <th width="20">SL.</th>
                    <th width="50">Chk</th>
                    <th width="100">Requisition No</th>
                    <th width="100">On Behalf Off</th>
                    <th width="200">Product Name</th>
                    <th width="50">Req Qty </th>
                    <th width="50">Delivery Qty </th>
                    <th width="50">Unit Price </th>
                    <?php if ($ProcessDeptId == 5) { ?>
                        <th>Supplier Name</th>
                        <th width="80">Ref. Date</th>
                        <th>Bill No</th>
                        <th>Branch/Dept</th>
                        <!--<th>CC-SOL</th>-->
                    <?php } ?>

                    </thead>
                    <tbody>

                        <?php
                        while ($rec = fetch_object($sql)) {
                            $totall++;
                            ?>

                            <tr class="datagrid-row">
                                <td><?php echo $totall; ?>.</td>
                                <td align="center"><input type="checkbox" name="orderids[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>" /></td>
                                <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                                    <input type='hidden' name='costCenter[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->COST_CENTER_ID; ?>' />
                                    <input type='hidden' name='orderid[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' />
                                </td> 
                                <td><?php echo $rec->FREE_TEXT; ?></td>
                                <td><?php echo $rec->PRODUCT_NAME; ?></td>
                                <td align="center"><?php echo $rec->QTY; ?></td>
                                <td align="center"><input type="text" name="delivery_qty[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" size="5" value="<?php echo $rec->APPROVE_QTY; ?>" /></td>
                                <td align="center"><input type="text" name="unitPrice[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" size="10" value="" /></td>
                                <?php if ($ProcessDeptId == 5) { ?>
                                    <td><?php comboBox("supplier_id[$rec->REQUISITION_DETAILS_ID]", $supplier_list, '', TRUE); ?></td>
                                    <td><input type="text" name="ref_date[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" size="" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/></td>
                                    <td><input type="text" name="bill_no[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" size="7"/></td>
                                    <td><?php comboBox("branchDeptId[$rec->REQUISITION_DETAILS_ID]", $branchDeptLis, $rec->BRANCH_DEPT_ID, TRUE, '', '', '', 'getCcSol($(this));'); ?></td>
                                    <!--<td><?php echo $rec->COST_CENTER_CODE . '->' . $rec->COST_CENTER_NAME . '(' . $rec->SOL_CODE . '->' . $rec->SOL_CODE . ')'; ?></td>-->


                                <?php } ?>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <input type="submit" class="button" value='Send To Line Manager' name='send' id="approved" />


            </form>
        </div>

    <?php } ?>
</div>  

<?php include '../body/footer.php'; ?>