<?php
include '../lib/DbManager.php';
include('../body/header.php');
$productid = getParam('productid');
$productname = findValue("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$productid'");
$employeeid = findValue("select EMPLOYEE_ID from master_user where USER_NAME='$user_name'");

$approved = getParam('approved');
$condition = getParam('condition');

if (!empty($approved)) {
    $orderids = getParam("orderids");
    $pending_pre = getParam("pending_pre");
    $delivery_qty = getParam("delivery_qty");
    $date = date('Y-m-d');


    foreach ($orderids as $key => $value) {
        $SqlHistory = "insert into app_product_delivery_history (req_id, product_id, delivery_qty, delivered_by, delivery_date)
		values('$value', '$productid', '$delivery_qty[$key]', '$employeeid', '$date')";
        sql($SqlHistory);
        //echo"<br/>";

        $pending = $pending_pre[$key] - $delivery_qty[$key];
        $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];
        
         $UpdateRequisition = "UPDATE requisition_details set 
            DELIVERED_QTY=IFNULL(DELIVERED_QTY,0)+$deliver_qty,
            DETAILS_STATUS=3, 
            STATUS_APP_LEVEL=1 
            WHERE PRODUCT_ID='$productid' and REQUISITION_ID='$key'";
        sql($UpdateRequisition);
        if ($pending < 1) {
            sql("update requisition set REQUISITION_STATUS_ID=6 where REQUISITION_ID='$key'");
        }
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";
}

$sql_produc_list = "SELECT pr.PRODUCT_CODE,
        si.PRODUCT_ID,
        si.REQUISITION_ID,
        so.REQUISITION_NO,
        max(so.PRIORITY_ID) as priorityid,
        si.status_app_level,
        pr.PRODUCT_NAME,
        sum(si.QTY) as quantity,
        sum(si.DELIVERED_QTY) as deliverd,
        '' AS 'Pending',
        (
            SELECT sum(QTY) as stock 
            FROM stockmove
            WHERE PRODUCT_ID=pr.PRODUCT_ID GROUP BY PRODUCT_ID
        ) AS 'stock',
        (
            SELECT sum(delivery_qty) as allocated 
            FROM app_product_delivery_history
            where product_id=pr.PRODUCT_ID AND challan_id IS NULL GROUP BY product_id
        ) AS 'allocated',
        '' AS 'available',
        e.FIRST_NAME, e.LAST_NAME,
        e.CARD_NO, ot.OFFICE_NAME, bd.BRANCH_DEPT_NAME



        from product pr
        LEFT JOIN requisition_details si on si.PRODUCT_ID=pr.PRODUCT_ID
        LEFT JOIN requisition so on so.REQUISITION_ID= si.REQUISITION_ID 
        LEFT JOIN employee e ON e.CARD_NO=so.CREATED_BY
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        WHERE REQUISITION_TYPE_ID =1 AND si.STATUS_APP_LEVEL = 1 
        AND pr.PRODUCT_TYPE_ID=1 AND so.PROCESS_DEPT_ID='$ProcessDeptId' AND so.cancelled=0 
            AND so.REQUISITION_STATUS_ID=5 AND si.PRODUCT_ID='$productid'
        GROUP BY so.REQUISITION_ID  HAVING quantity-deliverd > 0";

$sql = query($sql_produc_list);

//die();

?>
<div class="easyui-layout" style="width:950px; height:700px; margin: auto;">  
    <div data-options="region:'center',iconCls:'icon-ok'">  


        <div  title="Product List"> 

            <form name="frm" action="" method='POST'>
                <input type=hidden name='mode' value=''/>

                <?php
                if (getParam("msg") != "") {
                    echo "<h3 align='center'>Product has been send</h3>";
                }
                echo "<h3 align='center'>$productname</h3><br/>";
                echo "<h3 align='center'>Product Requisition Details</h3>";
                ?>


                <table class="ui-state-default">
                    <thead>
                    <th>Sl.</th>
                    <th width="100" align="left">Req.ID</th>
                    <th align="left">Req.Person</th>
                    <th align="left">Branch/ Department</th>
                    <th width="100" align="center">Priority</th>
                    <th width="50" align="center">Req Qty </th>
                    <th width="50" align="center">Pending Qty</th>
                    <th width="50" align="center">Delivery Qty </th>
                    <th width="52" align="center">Select</th>
                    </thead>
                    <?php
                    while ($rec = fetch_object($sql)) {
                        $totall++;
                        $pending_pre = $rec->quantity - $rec->delivery_qty;
                        ?>
                        <tr>
                            <td class="sn" align="center"><?php echo $totall; ?>.</td>
                            <td align="left"><a href='../requisition/reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"><?php echo $rec->REQUISITION_NO; ?></a>
                                <input type='hidden' name='orderid[<?php echo $rec->REQUISITION_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' />
                                <input type='hidden' name='product[<?php echo $rec->REQUISITION_ID; ?>]' value='<?php echo $rec->PRODUCT_ID; ?>' /></td> 
                            <td align="left"><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . ' (' . $rec->CARD_NO . ')'; ?></td>
                            <td  align="left"><?php echo $rec->OFFICE_NAME . '->' . $rec->BRANCH_DEPT_NAME; ?></td>
                            <td  align="center"><?php echo $rec->PRIORITY_ID; ?></td>
                            <td  align="center"><?php echo $rec->quantity; ?></td>
                            <td  align="center"><?php echo $rec->quantity - $rec->deliverd; ?></td>
                            <td  align="center">
                                <input type="text" name="delivery_qty[<?php echo $rec->REQUISITION_ID; ?>]" size="10" value="<?php echo $rec->quantity - $rec->deliverd; ?>"> 
                                <input type='hidden' name='pending_pre[<?php echo $rec->REQUISITION_ID; ?>]' value='<?php echo $pending_pre; ?>' />   
                            </td>
                            <td align="center"><input type="checkbox" name="orderids[<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->REQUISITION_ID; ?>" /></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>

                <p
                    <input type="hidden" name="productid" value="<?php echo $productid; ?>" />
                    <input type="hidden" name="count" value="<?php echo $totall; ?>" />
                    <input type="submit" class="button" value='Send to Store' name='approved' id="approved" />
                </p>

            </form>



        </div>  
    </div>  
</div>




<?php include '../body/footer.php'; ?>