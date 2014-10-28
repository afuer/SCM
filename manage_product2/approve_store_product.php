<?php
include '../lib/DbManager.php';
include "../body/header.php";



$req_id = getParam('req_id');
$approved = getParam('approved');
$details_status = getParam('details_status');
$approval_status = getParam('approval_status');


// Delevery Qty>0
$res = $details_status == 3 && $approval_status == 1 ? " HAVING quantities-deliverd>0" : "";

$productname = findValue("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$productid'");
$employeeid = findValue("SELECT EMPLOYEE_ID FROM employee WHERE CARD_NO='$user_name'");




if (!empty($approved)) {
    $orderids = getParam("orderids");
    $pending_pre = getParam("pending_pre");
    $delivery_qty = getParam("delivery_qty");
    $orderid = getParam('orderid');
    $date = date('Y-m-d');


    foreach ($orderids as $key => $value) {
        $sql = "insert into app_product_delivery_history (req_id, product_id, delivery_qty, delivered_by, delivery_date)
		values('$orderid[$key]', '$value', '$delivery_qty[$key]', '$employeeid', '$date')";
        sql($sql);

        $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];

        $sql_details = "update requisition_details set
            DELIVERED_QTY=IFNULL(DELIVERED_QTY,0)+$deliver_qty,
            DETAILS_STATUS=3, 
            STATUS_APP_LEVEL=1 
            WHERE REQUISITION_DETAILS_ID='$key'";
        $db->sql($sql_details);
        $requisition_id=$orderid[$key];
    }
    
    $pending = findValue("SELECT (IFNULL(SUM(QTY),0) -IFNULL(SUM(DELIVERED_QTY),0)) AS 'pending' FROM requisition_details WHERE REQUISITION_ID='$requisition_id';");
    if ($pending == 0) {
        $db->sql("update requisition set REQUISITION_STATUS_ID=5 where REQUISITION_ID='$requisition_id'");
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";
}

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

        WHERE so.REQUISITION_ID='$req_id' AND si.PROCESS_DEPT_ID='$ProcessDeptId'
        AND REQUISITION_STATUS_ID<>5
        GROUP BY si.PRODUCT_ID";
//AND dh.delivery_qty IS NULL

$sql = query($sql_produc_list);
?>
<div class="easyui-layout" style="width:1100px; margin: auto; height:550px;">  
    <div data-options="region:'center'" Title='Requisition Details List' style="background-color:white; "> 

        <form name="frm" action="" method='POST' autocomplete="off" class="formValidate">

            <?php
            if (getParam("msg") != "") {
                echo "<h3 align='center'>Product has been send</h3>";
            }
            echo "<h3 align='center'>$productname</h3><br/>";
            echo "<h3 align='center'>Requisition Details</h3>";
            ?>

            <table class="ui-state-default">
                <thead>
                <th width="20">SL.</th>
                <th width="100">Requisition No</th>
                <th>Req.Person</th>
                <th>Branch/Dept</th>
                <th width="200">Product Name</th>
                
                <th width="100">Stock Qty</th>
                <th width="80">Allocated Qty</th>
                <th width="60">Available Qty</th>
                
                <th width="50">Req.Qty</th>
                <th width="50">Delivery Qty </th>
                <th width="50">Select</th>
                </thead>
                <tbody>

                    <?php
                    while ($rec = fetch_object($sql)) {
                        $totall++;

                        $pending_pre = $rec->quantities - $rec->deliverd;
                        $available = $rec->stock - $rec->allocated;
                        ?>

                        <tr class="datagrid-row">
                            <td class="sn" align="center"><?php echo $totall; ?>.</td>
                            <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                                <input type='hidden' name='orderid[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' /></td> 
                            <td><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . ' (' . $rec->CARD_NO . ')'; ?></td>
                            <td><?php echo $rec->OFFICE_NAME . ' ' . $rec->BRANCH_DEPT_NAME; ?></td>
                            <td><?php echo $rec->PRODUCT_NAME; ?></td>
                            <td align="center"><?php echo $rec->stock; ?></td>
                            <td align="center"><?php echo $rec->allocated; ?></td>
                            <td align="center"><?php echo $available; ?></td>
                            <td align="center"><?php echo $rec->quantities; ?></td>
                            <td align="center"><input type="text" name="delivery_qty[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" size="10" value="<?php echo $rec->quantities - $rec->DELIVERED_QTY; ?>" min="1"/>
                                <input type='hidden' name='pending_pre[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $pending_pre; ?>' /></td>
                            <td align="center"><input type="checkbox" name="orderids[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>" /></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <p
                <input type="hidden" name="productid" value="<?php echo $productid; ?>" />
                <input type="hidden" name="count" value="<?php echo $totall; ?>" />
                <input type="submit" class="button" value='Send to Store' name='approved' id="approved" />
            </p>

        </form>
    </div>  
</div>  




<?php
include '../body/footer.php';
?>