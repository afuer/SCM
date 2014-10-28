<?php
include '../lib/DbManager.php';
set_time_limit(600);




function allocated($productid) {
    $value = findValue("select sum(delivery_qty) as allocated from app_product_delivery_history
		   where product_id='$productid' AND challan_id IS NULL group by product_id");
    return $value ? $value : 0;
}

function stock($productid) {
    $available = findValue("select sum(QTY) as stock from stockmove
	where PRODUCT_ID='$productid' group by PRODUCT_ID");
    return $available ? $available : 0;
}

function star_sign($priorityid) {
    $star_num = str_repeat("&#42;", $priorityid);
    $color = ($priorityid == 3) ? 'red' : 'black';
    $str_sign = "<font style='letter-spacing:3px; font-size:16px; font-weight:bold; color:$color'> $star_num </font>";
    return $str_sign;
}

$req_store = "select 
pr.PRODUCT_CODE,
si.PRODUCT_ID,
si.REQUISITION_ID,
max(so.PRIORITY_ID) as priorityid,
si.status_app_level,
pr.PRODUCT_NAME,
sum(si.QTY) as quantity,
sum(dh.deliverd) as deliverd



from product pr
left join requisition_details si on si.PRODUCT_ID=pr.PRODUCT_ID
left join requisition so on so.REQUISITION_ID= si.REQUISITION_ID 

left join (
select req_id, product_id, sum(delivery_qty) as deliverd
from app_product_delivery_history
group by req_id, product_id
) dh on dh.req_id=si.REQUISITION_ID and dh.product_id=si.PRODUCT_ID


WHERE REQUISITION_TYPE_ID =1 and dh.deliverd IS NOT NULL  and si.QTY > dh.deliverd and si.status_app_level > 0 
AND pr.PRODUCT_TYPE_ID=1 AND so.PROCESS_DEPT_ID='1' AND so.cancelled=0 
GROUP BY pr.PRODUCT_ID having quantity-deliverd > 0 ORDER BY si.REQUISITION_ID";
//$requisition_routeid

$sql = $db->query($req_store);
?>

<form action="add_suppliers2.php" name="myform" method='post'>
    <table class="ui-state-default" style="width:100%">
        <thead>
            <th width='20' align="center">Sl.</th>
            <th width='100' align="left">Product No</th>
            <th align="left">Product Name</th>
            <th width='12%' align="center">Pending Qty</th>         
            <th width='16%' align="center">Stock Qty</th>  
            <th width='10%' align="center">Allocated Qty </th>
            <th width='13%' align="center">Available Qty</th>  
        </thead>
        <?php
        
        while ($rec = fetch($sql)) {
            $pending = $rec->quantity - $rec->deliverd;
            $stock = stock($rec->PRODUCT_ID);
            $allocated = allocated($rec->PRODUCT_ID);
            $available = $stock - $allocated;
            ?> 
            <tr>
                <td class="sn" align="center"><?php echo++$sl; ?>.</td>
                <td align="left"><?php echo $rec->PRODUCT_CODE; ?></td>
                <td  align="left"><?php echo $rec->PRODUCT_NAME; ?></td>
                <td align="center"><a href="approve_store_pending_product.php?productid=<?php echo $rec->PRODUCT_ID; ?>&condition=<?php echo "deliverd IS NOT NULL and"; ?>" target="_blank"><?php echo $pending; ?></a></td>
                <td align="center"><?php echo $stock; ?></td>
                <td align="center"><?php echo $allocated; ?></td>
                <td align="center"><?php echo $available; ?></td>
            </tr> 
            <?php
        }

        
        ?>

    </table>
</form>



