<?php
include '../lib/DbManager.php';


$productid = getParam('productid');
$approved = getParam('approved');
$details_status = getParam('details_status');
$approval_status = getParam('approval_status');

$supplier_list = $db->rs2array("SELECT s.SUPPLIER_ID, s.SUPPLIER_NAME
FROM supplier_price sp
INNER JOIN supplier s ON s.SUPPLIER_ID=sp.SUPPLIER_ID
WHERE sp.PRODUCT_ID='$productid' GROUP BY s.SUPPLIER_ID");

$costCenterList = $db->rs2array('SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME');


// Delevery Qty>0
$res = $details_status == 3 && $approval_status == 1 ? " HAVING quantities-deliverd>0" : "";

$productname = findValue("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$productid'");
$employeeid = findValue("SELECT EMPLOYEE_ID FROM employee WHERE CARD_NO='$user_name'");




if (!empty($approved)) {
    $orderids = getParam("orderids");
    $pending_pre = getParam("pending_pre");
    $delivery_qty = getParam("delivery_qty");


    $date = date('Y-m-d');


    foreach ($orderids as $key => $value) {
        $sql = "insert into app_product_delivery_history (req_id, product_id, delivery_qty, delivered_by, delivery_date, CREATED_DATE)
		values('$value', '$productid', '$delivery_qty[$key]', '$employeeid', '$date', NOW())";
        sql($sql);

        $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];

        $pending = $pending_pre[$key] - $delivery_qty[$key];
        $sql_details = "update requisition_details set
            DELIVERED_QTY=IFNULL(DELIVERED_QTY,0)+$deliver_qty,
            DETAILS_STATUS=3, 
            STATUS_APP_LEVEL=1 
            WHERE PRODUCT_ID='$productid' and REQUISITION_ID='$value'";
        $db->sql($sql_details);
        $db->sql("update requisition set REQUISITION_STATUS_ID=5 where REQUISITION_ID='$value'");
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";
}

$sql_produc_list = "SELECT si.PRODUCT_ID,
si.REQUISITION_ID,
pr.PRODUCT_NAME,
p.PRIORITY_ID, 
sum(si.QTY) as quantities,
so.CREATED_BY,
dv.DIVISION_NAME, 
so.OFFICE_TYPE_ID, 
so.BRANCH_DEPT_ID,
so.REQUISITION_NO,
e.FIRST_NAME, e.LAST_NAME,
e.CARD_NO, ot.OFFICE_NAME, bd.BRANCH_DEPT_NAME, dh.deliverd

from requisition_details si
left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
left join requisition so on si.REQUISITION_ID=so.REQUISITION_ID
LEFT JOIN priority p ON p.PRIORITY_ID = so.PRIORITY_ID
left join division dv on dv.DIVISION_ID=so.DIVISION_ID
LEFT JOIN ( SELECT IFNULL(SUM(sdh.delivery_qty),0) AS deliverd, sdh.req_id
FROM app_product_delivery_history AS sdh 
INNER JOIN requisition_details rd ON rd.REQUISITION_ID=sdh.req_id AND rd.PRODUCT_ID=sdh.product_id
WHERE rd.DETAILS_STATUS='$details_status' AND rd.STATUS_APP_LEVEL='$approval_status' AND rd.PRODUCT_ID ='$productid'
GROUP BY rd.REQUISITION_ID
) dh ON dh.req_id=so.REQUISITION_ID
LEFT JOIN employee e ON e.CARD_NO=so.CREATED_BY
LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID

WHERE  so.PROCESS_DEPT_ID='$ProcessDeptId' and si.DETAILS_STATUS='$details_status' 
    and si.STATUS_APP_LEVEL='$approval_status' and pr.PRODUCT_TYPE_ID='1' 
AND so.cancelled=0 and si.PRODUCT_ID ='$productid' 
    group by si.REQUISITION_ID";
//AND dh.delivery_qty IS NULL

$sql = query($sql_produc_list);

include "../body/header.php";
?>

<div class="easyui-layout" style="width:100%; height:600px;">  
    <div title="Search Option" data-options="region:'north',split:true, collapsed:true" title="West" style="height: 200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">
            DD

        </div>  
    </div>

    <div data-options="region:'south',split:true" style="height:50px;">Button Part</div>

    <div data-options="region:'east', split:true, collapsed:true" title="East" style="width:250px;">  
        <ul class="easyui-tree" data-options="url:'', animate:true, dnd:true"></ul>  
    </div> 

    <div data-options="region:'west',split:true, collapsed:true" title="West" style="width:200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">  
            <div title="Title1" style="padding:10px;">  
                content1  
            </div>  
            <div title="Title2" data-options="selected:true" style="padding:10px;">  
                content2  
            </div>  
            <div title="Title3" style="padding:10px">  
                content3  
            </div>  
        </div>  
    </div>

    <div data-options="region:'center'"> 
        <div title="Search List" data-options="region:'center'">

        </div> 
        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  

            <div title="Requisition Details List" data-options="selected:true">  


                <form name="frm" action="" method='POST' autocomplete="off">

                    <?php
                    if (getParam("msg") != "") {
                        echo "<h3 align='center'>Product has been send</h3>";
                    }
                    echo "<h3 align='center'>$productname</h3><br/>";
                    echo "<h3 align='center'>Product Requisition Details</h3>";
                    ?>

                    <table class="ui-state-default">
                        <thead>
                        <th width="20">SL.</th>
                        <th width="100">Req.ID</th>
                        <th>Req.Person</th>
                        <th>Branch/ Department</th>
                        <th width="50">Priority</th>
                        <th width="50">Req.Qty</th>
                        <th width="50">Delivery Qty </th>
                        <th width="50">Select</th>
                        </thead>
                        <tbody>

                            <?php
                            while ($rec = fetch_object($sql)) {
                                $totall++;

                                $pending_pre = $rec->quantities - $rec->deliverd;
                                ?>
                                <tr class="datagrid-row">
                                    <td class="sn" align="center"><?php echo $totall; ?>.</td>
                                    <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                                        <input type='hidden' name='orderid[<?php echo $rec->REQUISITION_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' /></td> 
                                    <td align="left"><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . ' (' . $rec->CARD_NO . ')'; ?></td>
                                    <td  align="left"><?php echo $rec->OFFICE_NAME . ' ' . $rec->BRANCH_DEPT_NAME; ?></td>
                                    <td  align="center"><?php echo $rec->priority; ?></td>
                                    <td  align="center"><?php echo $rec->quantities - $rec->deliverd; ?></td>
                                    <td  align="center"><input type="text" name="delivery_qty[<?php echo $rec->REQUISITION_ID; ?>]" size="10" value="<?php echo $rec->quantities - $rec->deliverd; ?>">
                                        <input type='hidden' name='pending_pre[<?php echo $rec->REQUISITION_ID; ?>]' value='<?php echo $pending_pre; ?>' /></td>
                                    <td align="center"><input type="checkbox" name="orderids[<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->REQUISITION_ID; ?>" /></td>
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
    </div>  
</div>




<?php
include '../body/footer.php';
?>