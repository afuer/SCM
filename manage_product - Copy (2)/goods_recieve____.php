<?php
include_once '../lib/DbManager.php';
include("../body/header.php");
$search = getParam('search');
$goods_status = getParam('goods_status');
$date = date('Y-m-d');
$startdate = getParam('startdate');
//$startdate = $startdate == '' ? FirstDayLastTwoMonth() : $startdate;
$enddate = getParam('enddate');
//$enddate = $enddate == '' ? lasDayMonth() : $enddate;

if (isset($search)) {
    if ($goods_status == 4) {
        $status_app_level = 3;
    } else if ($goods_status == 5) {
        $status_app_level = 4;
    } else if ($goods_status == 6) {
        $goods_status = '4,5';
    }
} else {
    $status_app_level = 3;
    $goods_status = 4;
}

$btnchallan = getParam('btnchallan');

if (!empty($btnchallan)) {
    $done = getParam('done');
    $orderid = getParam('orderid');
    $quantity = getParam('quantity');
    $createdby = getParam('createdby');
    $poid = getParam('poid');
    $unitprice = getParam('unitprice');
    $challan_no = getParam('challan_no');
    //print_r($challan_no);

    foreach ($done as $key => $value) {

        sql("insert stockmove (productid, diff, narrative, purchaseorderid, createdby, locationid, deliverd, movement_date, salesorderid)
            values($value, '$quantity[$value]', 'Store Receive For PR No-$poid[$value]', $poid[$value], $createdby[$value], '1', '1', now(), '$orderid[$value]' )");

        sql("update product set purchase_price='$unitprice[$value]' where productid=$value");
        sql("UPDATE requisition_details SET UNIT_PRICE='$unitprice[$value]' WHERE PRODUCT_ID='$value'");

        $po_sql = "update purchaseorder_item_delivery set status=5, challanno='$challan_no[$value]', receive_date='$date' where poid=$poid[$value] and orderid=$orderid[$value] and productid=$value";
        sql($po_sql);

        $req_qty = findValue("select QTY from requisition_details where REQUISITION_ID='$orderid[$value]' and PRODUCT_ID='$value'");
        $deliver_qty = findValue("select sum(quantity) as quantity from purchaseorder_item_delivery where productid=$value and orderid=$orderid[$value] and status=5");
        $remaining = $req_qty - $deliver_qty;


        $sql_up = "UPDATE requisition_details SET STATUS_APP_LEVEL=4, LAST_PURCHASE_DATE='$date'
                    WHERE STATUS_APP_LEVEL=3 AND PRODUCT_ID='$value' AND REQUISITION_ID='$orderid[$value]'";

        if ($remaining < 1) {
            sql($sql_up);
        } else {
            sql("UPDATE requisition SET REQUISITION_STATUS_ID=4 WHERE REQUISITION_ID='$orderid[$value]'");
        }

        ////////////////////////////////////////////////

        total_received_pr();
        partly_received_requisition();
        $po_sql = "update purchaseorder_item set status=5 where poid='$poid[$value]' and productid='$value'";
        sql($po_sql);
        partly_received_po();
        fully_received_po();
    }
}

$sql = "select pur.purchase_order_id,
        pur.comparison_id,
        pri.quantity,
        pri.status,
        pur.supplier_id,
        pri.orderid,
        pri.productid,
        pri.challanno,
        pur.created_by,	 
        sum(pri.quantity*pri.unitprice) as total,
        s.REQUISITION_ID,
        s.REQUISITION_NO,
        DATE_FORMAT(pri.receive_date,'%e-%b %Y') as receive_date
        from purchase_order pur
        left join purchaseorder_item_delivery pri on pur.purchase_order_id  = pri.poid
        inner join requisition s on s.REQUISITION_ID = pri.orderid
        where PROCESS_DEPT_ID ='$ProcessDeptId' and pri.status in ($goods_status) 
            group by pri.poid, pri.productid";


$query = query($sql);


$goods_status_list = array(array('4', 'Pending'), array('5', 'Received'), array('6', 'All'));



?>
<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Delivery Products' style="padding: 10px 10px; background-color:white; "> 

        <form action="" method="POST">
            <fieldset>
                <table width="100%" border="0">
                    <tr>
                        <td width="100">Status</td>
                        <td><?php comboBox('goods_status', $goods_status_list, $goods_status, TRUE); ?></td>
                        <td>Interval</td>
                        <td width="100"><input type="text" name="startdate" value="<?php echo $startdate; ?>" class="easyui-datebox"/> </td>
                        <td>To</td>
                        <td><input type="text" name="enddate" value="<?php echo $enddate; ?>" class="easyui-datebox"/></td>
                    </tr>  
                </table>
                <button type="submit" name="search" class="button" value="Search">Search</button>
            </fieldset>
            <table width="100%" class="ui-state-default">
                <thead>
                    <tr>
                        <th width='30'>SL.</th>
                        <th width='100'>PR No </th>
                        <th width='100'>Receive Date</th>
                        <th width='19%'>Supplier</th>         
                        <th width='20%'>Product</th>         
                        <th width='6%'>Qty</th>
                        <th width='20%'>Challan No </th>  
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rec = fetch_object($query)) {
                        $sl++
                        ?> 
                        <tr>
                            <td class="sn"><?php echo $sl; ?>.</td>
                            <td><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a></td>
                            <td><?php echo $rec->receive_date; ?></td>
                            <td align="left"><?php echo findValue("select SUPPLIER_NAME from supplier where SUPPLIER_ID='$rec->supplier_id'"); ?></td>
                            <td align="left"><?php echo findValue("select PRODUCT_NAME from product where PRODUCT_ID='$rec->productid'"); ?></td>
                            <td align="center"><?php echo $rec->quantity; ?></td>
                            <td align="center">
                                <?php
                                echo $rec->challanno;
                                if ($rec->status == 4) {
                                    ?>
                                    <input type="text" name="challan_no[<?php echo $rec->productid; ?>]" />
                                <?php } ?>
                            </td>
                            <td width="9%" align="center">
                                <?php
                                if ($rec->status == 4) {
                                    ?>
                                    <input type="checkbox" name="done[<?php echo $rec->productid; ?>]" id="done[<?php echo $rec->productid; ?>]" value="<?php echo $rec->productid; ?>" />
                                    <input type="hidden" name="poid[<?php echo $rec->productid; ?>]" id="poid[<?php echo $rec->productid; ?>]" value="<?php echo $rec->purchase_order_id; ?>"/>
                                    <input type="hidden" name="orderid[<?php echo $rec->productid; ?>]" value="<?php echo $rec->REQUISITION_ID; ?>"/>
                                    <input type="hidden" name="quantity[<?php echo $rec->productid; ?>]" value="<?php echo $rec->quantity; ?>"/>
                                    <input type="hidden" name="createdby[<?php echo $rec->productid; ?>]" value="<?php echo $rec->created_by; ?>"/>
                                    <input type="hidden" name="unitprice[<?php echo $rec->productid; ?>]" value="<?php echo $rec->unitprice; ?>"/>
                                <?php } ?>   
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="4" align="right">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center" colspan="3"><input type="submit" name="btnchallan" value="Received"></td>
                    </tr>
                </tbody>
            </table>


        </form>
    </div>
</div>
<?php include("../body/footer.php"); ?>