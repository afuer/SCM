<?php
include '../lib/DbManager.php';
//include('../body/functions.php');
include("../body/header.php");
$poid = getParam('poid');
$save = getParam('save');


if (isset($save)) {
    $orderid = getParam('orderid');
    $supplierid = getParam('supplierid');
    $productid = getParam('productid');
    $quantity = getParam('qty');
    $unitprice = getParam('unitprice');
    $requisitionId = getParam('requisitionId');

    if (isset($orderid)) {
        foreach ($orderid as $key => $value) {

            $sql = "insert into purchaseorder_item_delivery(poid, orderid, supplierid, productid, quantity, unitprice, status)
		values('$poid', '$requisitionId[$key]', '$supplierid[$key]', '$productid[$key]', '$quantity[$key]', '$unitprice[$key]', 4 )";
            sql($sql);
            //echo '<br/>';
        }
        echo "<script type='text/javascript'>window.opener.parent.location.reload();</script>";
        echo "<script type='text/javascript'>window.close();</script>";
    }
}
?>
<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Purchase Order Details' style="padding: 10px 10px; background-color:white; "> 

        <form name="frm_committee" method="post" class="formValidate" autocomplete="off">
            <table class="ui-state-default">
                <thead>
                    <tr>
                        <th field="1" width="30">SL.</th>
                        <th field="2" width="100">Requisition No</th>
                        <th field="3" width="200">Requisition By </th>
                        <th field="5">Product Name </th>
                        <th field="6" width="80">Req. qty </th>
                        <th field="7" width="80">PO Qty</th>
                        <th field="8" width="100">Deliverable  Qty </th>
                        <th field="9" width="50">Chk</th>
                    </tr>
                </thead>
                <?php
                $query_pro = query("SELECT 
				pr.PRODUCT_NAME,
			  prc.unit_price,
			  prc.product_id,
                          prc.qty as quantity,
			  po.orderids,
                          r.CREATED_BY,
			  po.supplier_id, pcq.requisition_id,
                          price_comparison_id,
                          (
                                select sum(quantity) as quantity from purchaseorder_item_delivery 
                                where poid='$poid' and productid=prc.product_id and orderid=pcq.requisition_id
                           ) AS deliver_qty,
                           r.REQUISITION_NO, r.REQUISITION_ID

			  FROM purchase_order_details prc
			  left join purchase_order po on po.purchase_order_id=prc.purchase_order_id
			  left join product pr on pr.PRODUCT_ID=prc.product_id
                          LEFT JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=po.comparison_id
                          LEFT JOIN requisition r ON r.REQUISITION_ID=pcq.requisition_id
			   where prc.purchase_order_id='$poid' group by prc.product_id");

                while ($rec = fetch_object($query_pro)) {

                    $req_qty = find("SELECT QTY, r.BRANCH_DEPT_ID
                            FROM requisition_details rd
                            INNER JOIN requisition r ON r.REQUISITION_ID=rd.REQUISITION_ID
                            WHERE rd.REQUISITION_ID='$rec->requisition_id' AND PRODUCT_ID='$rec->product_id'  
                            AND  rd.STATUS_APP_LEVEL=3");

                    $del_qty = findValue("SELECT SUM(qty) FROM purchase_order_details WHERE purchase_order_id='$poid' AND product_id='$rec->product_id'");

                    $deliverable_quantity = ($rec->quantity > $del_qty) ? $del_qty : $rec->quantity;
                    $remaining = $deliverable_quantity - $rec->deliver_qty;

                    if ($deliverable_quantity) {
                        ?>
                        <tr>
                            <td class="sn"><?php echo++$sl; ?></td>
                            <td><a href="../manage_product/reco_details.php?reco_id=<?php echo $rec->requisition_id; ?>" target="_blank"><?php echo $rec->REQUISITION_NO; ?></a></td>
                            <td><?php echo user_identityById($rec->CREATED_BY); ?></td>
                            <td><?php echo $rec->PRODUCT_NAME; ?></td>
                            <td align="right"><?php echo $req_qty->QTY; ?></td>
                            <td align="right"><?php echo $rec->quantity; ?></td>
                            <td align="right"> 
                                <input type="text" name="qty[<?php echo $sl; ?>]" size="10" value="<?php echo $remaining; ?>" max="<?php echo $remaining; ?>" min="1"/>
                                <input type="hidden" name="poid" value="<?php echo $poid; ?>"/>
                                <input type="hidden" name="supplierid[<?php echo $sl; ?>]" value="<?php echo $rec->supplier_id; ?>"/>
                                <input type="hidden" name="requisitionId[<?php echo $sl; ?>]" value="<?php echo $rec->requisition_id; ?>"/>
                                <input type="hidden" name="productid[<?php echo $sl; ?>]" value="<?php echo $rec->product_id; ?>"/>
                                <input type="hidden" name="unitprice[<?php echo $sl; ?>]" value="<?php echo $rec->unit_price; ?>"/>
                            <td>
                                <?php
                                if ($remaining > 0) {
                                    ?>
                                    <input type="checkbox"  name="orderid[<?php echo $sl; ?>]" />
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>   
                <input type="hidden" name="compersionid" value="<?php echo getParam('compersionid'); ?>" />         
            </table>                
            <br/>
            <input type="submit" name="save" class="button" value="Save"/>
            <input type="hidden" value="<?php echo $_REQUEST["compersionid"]; ?>" name="compersionid"/>
            <input type="hidden" value="<?php echo $mode; ?>" name="mode"/>
        </form>
    </div
</div
<?php include("../body/footer.php"); ?>