<?php
include '../lib/DbManager.php';
include '../lib/ibrahimconvert.php';

$comparisonid = getParam("comparisonid");
$supplier_id = getParam("supplier_id");
$orderids = getParam("orderids");
$quantity = getParam("quantity");
$unite_price = getParam("unite_price");
$product = getParam('product');
$delivery_date = getParam('delivery_date');
$discount_rate = getParam('discount_rate');


$delivery_date = $delivery_date == "" ? date("Y-m-d") : $delivery_date;


$toc_con = getParam('toc_con');
$w_toc = getParam('w_toc');
$forwarding_text = getParam('forwarding_text');
$date = date("Y-m-d");
$id = getParam('id');



$query_price = query("select 
		  prd.productid,
		  prd.supplier_id,
		  ref.reference
		  from 
		  price_comparison prc
		  left join price_comparison_details prd on prc.comparisonid=prd.comparison_id
		  left join comparative_referance ref on ref.comparison_id=prc.comparisonid
		   where prd.comparison_id='$comparisonid' and prd.selected=1 and prd.supplier_id='$supplier_id'");
$sup_rec = fetch_object($query_price);

$productid = $sup_rec->productid;
$supplierID = $sup_rec->supplier_id;
$reference = $sup_rec->reference;

//--------------------------

$po_id = NextId('purchase_order', 'purchase_order_id');


include("../body/header.php");
?>

<link rel="stylesheet" type="text/css" media="all" href="notdemo.css" />


<?php
$qq = query("Select * from po_templates where id = 4");
$dd = fetch_object($qq);

$q_supp = query("SELECT SUPPLIER_ADDRESS, SUPPLIER_NAME
FROM supplier WHERE SUPPLIER_ID = '$supplierID'");
$d_supp = fetch_object($q_supp);

$supplier_name = $d_supp->SUPPLIER_NAME;
$supplier_contact = $d_supp->SUPPLIER_ADDRESS;
$supplier_address = $d_supp->SUPPLIER_ADDRESS;

$date = date("D, d-M-Y");

if ($dd->toc_id != "") {
    $q_toc = query("Select w_toc from po_toc where id = $dd->toc_id") or die(mysql_error());
    $d_toc = fetch_object($q_toc);
    $toc = $d_toc->w_toc;
} else {
    $toc = "";
}



$from = array("&lt;::product_list::&gt;", "&lt;::supplier_name::&gt;", "&lt;::supplier_contact::&gt;", "&lt;::supplier_address::&gt;", "&lt;::date::&gt;", "&lt;::terms::&gt;");
$change = array($product_list, $supplier_name, $supplier_contact, $supplier_address, $date, $toc);

if ($_POST["confirm"]) {

    $net_disc_rate = getParam('net_disc_rate');
    $net_total_disc = getParam('net_total_disc');
    $vat_rate = getParam('vat_rate');
    $net_total_vat = getParam('net_total_vat');
    $today = date("Y-m-d");

    $maxId = NextId('purchase_order', 'purchase_order_id');
    $orderId = OrderNo($maxId);

    $sql_p = "insert into purchase_order (order_no, order_date, office_type, branch_dept_id, comparison_id, supplier_id, orderids, created_by, supp_ref, discount, total_discount, vat, total_val)
			values('$orderId', '$today', '$office_types', '$branch_dept_ids', $comparisonid, $supplierID, '$orderid_list', '$employeeId', '$reference', '$net_disc_rate', '$net_total_disc', '$vat_rate', '$net_total_vat')";
    sql($sql_p);
    $poid = insert_id();

    foreach ($product as $key => $value) {

        $sql_p_det = "Insert into purchase_order_details(purchase_order_id, office_type_id, branch_dept_id, product_id, qty, unit_price, discount, details_status)
			values ($poid, '1', '', '$value', '$quantity[$key]', '$unite_price[$key]', '$discount_rate[$key]', 5)";
        sql($sql_p_det);
    }

    //---------------save terms & condition into folder as a html file--------
    //$w_toc = rtrim($w_toc, "\r\n");
    $w_toc = str_replace(array(chr(13) . chr(10), chr(13), chr(10), chr(92) . chr(114) . chr(92) . chr(110)), '<br />', $w_toc);


    $folder = "../files/po/";
    $myFile = $folder . $poid . "_tcc.html";
    $fh = fopen($myFile, 'w') or die("can't open file");
    $stringData = $w_toc;
    fwrite($fh, $stringData);
    fclose($fh);

    $myFile2 = $folder . $poid . "_gtc.html";
    $fh2 = fopen($myFile2, 'w') or die("can't open file2");
    $stringData2 = nl2br($w_toc);
    fwrite($fh2, $stringData);
    fclose($fh2);


    $p_toc = "Insert into purchaseorder_tcc( poid, tcc, gr_tcc, forwarding_text)
			values ($poid, '$myFile', '$myFile2', '$forwarding_text')";
    sql($p_toc);
    sql("Update price_comparison_details set poid='$poid' where comparison_id='$comparisonid' and supplier_id='$supplier_id'");
    sql("Update price_comparison set status=4 where comparisonid='$comparisonid'");
    echo "<script type='text/javascript'>location.replace('index.php')</script>";
}
?>

<script language="javascript">
<?php
$calc_query = query("SELECT 
                        pr.PRODUCT_NAME,
                  prd.quantity,
                  prd.unite_price,
                  prd.supplier_id,
                  (prd.quantity*prd.unite_price) as total
                  from 
                  price_comparison prc
                  left join price_comparison_details prd on prc.comparisonid=prd.comparison_id
                  left join product pr on pr.PRODUCT_ID=prd.productid
                   where prc.comparisonid='$comparisonid' and prd.selected=1 and prd.supplier_id='$supplier_id' group by prd.productid");

$y = 0;
while ($cal = fetch_object($calc_query)) {
    $y++;
    ?>
        function totalprice<?php echo $y; ?>() {
            var bill_amount = parseFloat(document.autoSumForm.bill_amount<?php echo $y; ?>.value);
            var discount_rate = parseFloat(document.autoSumForm.discount_rate<?php echo $y; ?>.value);
            document.autoSumForm.total_disc<?php echo $y; ?>.value = (discount_rate / 100) * bill_amount;
            var total_disc = parseFloat(document.autoSumForm.total_disc<?php echo $y; ?>.value);
            document.autoSumForm.subtotal<?php echo $y; ?>.value = bill_amount * 1 - total_disc * 1;
            //document.autoSumForm.netamount.value      = Math.round(netamount, 2);
        }
<?php }
?>
    function gross_total()
    {
<?php
for ($n = 1; $n <= $y; $n++) {
    $subtotal[] = "subtotal" . $n;
    ?>
            var subtotal<?php echo $n; ?> = parseFloat(document.autoSumForm.subtotal<?php echo $n; ?>.value);
    <?php
}

$subtotals = implode("+", $subtotal);
$subtotals = $subtotals == '' ? 0 : $subtotals;
?>
        document.autoSumForm.gross_value.value = <?php echo $subtotals; ?>;
        document.autoSumForm.netsubtotal.value = <?php echo $subtotals; ?>;

    }
    function subtotalprice()
    {
        var gross_value = parseFloat(document.autoSumForm.gross_value.value);
        var net_disc_rate = parseFloat(document.autoSumForm.net_disc_rate.value);
        document.autoSumForm.net_total_disc.value = (net_disc_rate / 100) * gross_value;
        var net_total_disc = parseFloat(document.autoSumForm.net_total_disc.value);

        var vat_rate = parseFloat(document.autoSumForm.vat_rate.value);
        document.autoSumForm.net_total_vat.value = (vat_rate / 100) * gross_value;
        var net_total_vat = parseFloat(document.autoSumForm.net_total_vat.value);
        document.autoSumForm.netsubtotal.value = (gross_value * 1 - net_total_disc * 1) + (1 * net_total_vat);
    }
    function subtotaldisc()
    {
        var gross_value = parseFloat(document.autoSumForm.gross_value.value);
        var net_total_disc = parseFloat(document.autoSumForm.net_total_disc.value);
        document.autoSumForm.net_disc_rate.value = (net_total_disc / gross_value) * 100;
    }
</script>


<script type="text/javascript" src="../manage_product/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../manage_product/js/ckeditor/config.js"></script>


<div class="easyui-layout" style="margin: auto; height:900px;">  
    <div data-options="region:'center'" Title='Purchase Order Details' style="padding: 10px 10px; background-color:white; "> 

        <form action="temp_view.php" name="autoSumForm" method="post" class="form">
            <table class="table">
                <tr>
                    <td colspan="2" valign="top"><img src="../public/images/CityBank.png" height="50" /></td>
                    <td></td>
                    <td width="200" align="right"><b>Prime Bank Center</b><br /> 
                        136, Gulshan Avenue, Gulshan-2, <br>Dhaka-1212, Bangladesh<br>Web: www.thecity.com.bd</td>
                </tr>

                <tr>
                    <td align="left">To<br />
                        <?php
                        echo $supplier_name . "<br />";
                        echo $supplier_address . "<br />";
                        ?>
                    </td>
                    <td></td>
                    <td></td>
                    <td align="right">WO/PO No : <?php echo evaluation_no($po_id); ?><br />

                        Date : <?php echo $date; ?><br />
                        Supplier Ref : <?php echo $reference; ?><br />
                        PR : <?php
                        $orders = explode(",", $orderid_list);

                        $sql_result = query("SELECT pcq.requisition_id, REQUISITION_NO
                        FROM price_comparison_pro_req_qty pcq
                        LEFT JOIN requisition r ON r.REQUISITION_ID=pcq.requisition_id
                        WHERE price_comparison_id='$comparisonid' GROUP BY requisition_id");

                        $count = 0;
                        $order_num = 0;
                        while ($row = mysql_fetch_object($sql_result)) {

                            echo "<a href='../manage_product/reco_details.php?reco_id=$row->requisition_id&productid=$productid' target='_blank'>$row->REQUISITION_NO</a>";

                            $order_num++;
                            if ($order_num == 3) {
                                echo "<br />";
                                $order_num = 0;
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <textarea name="forwarding_text" style="width: 100%; height: 120px;">Dear Mr/Mrs. <?php echo $supplier_contact; ?>
        
            We have the pleasure in submitting our quotation as requested. Please contact me if you have any queries.More text here.. Please contact me if you have any queries.More text here.sted. Please contact me if you have any queries.More text here.. Please contact me if you have any queries.More text here. sted. Please contact me if you have any queries.More text here.. Please contact me if you have any queries.More text here. 
            </textarea>


            <table width="100%" class="ui-state-default">
                <thead>
                <th width="20">SL.</th>
                <th>Product</th>
                <th width="60" align='right'>Qty</th>
                <th width="60" align='right'>Unit Price</th>
                <th width="100" align='right'>WO Value </th>
                <th width="10" align='right'>Discount(%)</th>
                <th width="100" align='right'>Disc. Amount </th>
                <th width="100" align='right'>Total Value </th>
                </thead>
                <?php
                $query_pro = query("SELECT prd.productid,
                pr.PRODUCT_NAME,
                prd.quantity,
                prd.unite_price,
                prd.supplier_id,
                (prd.quantity*prd.unite_price) as total

                FROM price_comparison prc
                left join price_comparison_details prd on prc.comparisonid=prd.comparison_id
                left join product pr on pr.PRODUCT_ID=prd.productid
                where prc.comparisonid='$comparisonid' and prd.selected=1 and 
                prd.supplier_id='$supplier_id' group by prd.productid");

                $grand_total = 0;
                while ($rec = fetch_object($query_pro)) {
                    $x++;
                    $table_list = $table_list;
                    ?>
                    <tr>
                        <td class='sn'><?php echo $x; ?></td>
                        <td>
                            <?php echo $rec->PRODUCT_NAME; ?>
                            <input type='hidden' name='product[]' value='<?php echo $rec->productid; ?>' />
                        </td>
                        <td align='right'><?php echo $rec->quantity; ?>
                            <input type='hidden' name='quantity[]' value='<?php echo $rec->quantity; ?>' />
                        </td>


                        <td align='right'>
                            <?php echo number_format($rec->unite_price, 2, '.', ',') ?>
                            <input type='hidden' name='unite_price[]' value='<?php echo $rec->unite_price; ?>' />
                        </td>
                        <td align='right'><?php echo number_format($rec->total, 2, '.', ','); ?>
                            <input type="hidden" name="bill_amount" id="bill_amount<?php echo $x; ?>" value="<?php echo $rec->total; ?>" />				</td>
                        <td align='right'>
                            <input type="text" name="discount_rate<?php echo $x; ?>" class="discount_rate" value="0" size="4" /></td>
                        <td align='right'><input type="text" class="total_disc" name="total_disc<?php echo $x; ?>"  id="total_disc<?php echo $x; ?>" value="0" size="8" /></td>
                        <td align='right'><input type="text" class="subtotal" name="subtotal<?php echo $x; ?>" readonly="1" value="<?php echo $rec->total; ?>"/></td>
                    </tr>

                    <?php
                    $grand_total +=$rec->total;

                    $convert = new Ibiconvert();
                    //echo "My convertion : " . $convert->val($values) . "<br>";

                    list($main, $decimal) = explode(".", $grand_total);
                    //
                    $tk = $convert->val($main) . " taka ";
                    if ($decimal != "") {
                        $decimal = $convert->val($decimal);
                        $decimal = "and $decimal paisa ";
                    }
                }

//$vat = 15;
//$nettotal = $grand_total + ($grand_total * $vat)/100;

                $table_list = $table_list;
                ?>


                <tr>
                    <td align='right' style='border-top:2px solid #000;'></td>
                    <td align='right' style='border-top:2px solid #000;'></td>
                    <td colspan='4' align='right' style='border-top:2px solid #000;'><b> Amount:</b></td>
                    <td align='right' style='border-top:2px solid #000;'><strong><?php echo formatMoney($grand_total); ?></strong></td>
                    <td align='right' style='border-top:2px solid #000;'><b><input type="text" class="price" name="gross_value" value="<?php echo formatMoney($grand_total); ?>" /></b>					</td>
                </tr>

                <tr>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td colspan=4  align=right ><strong>Discount(%)</strong></td>
                    <td align=right style='font-size:16px;'><input type="text" class="price number" name="net_disc_rate" id="net_disc_rate" value="0" onKeyUp="subtotalprice();" size="8" /></td>
                    <td align=right style='font-size:16px;'><input type="text" class="price number" name="net_total_disc" id="net_total_disc" value="0" onKeyUp="subtotaldisc();" /></td>
                </tr>
                <tr>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td colspan=4  align=right ><strong>VAT(%)</strong></td>
                    <td align=right style='font-size:16px;'><input type="text" class="number" name="vat_rate" id="vat_rate" value="0" onKeyUp="subtotalprice();" size="8" /></td>
                    <td align=right style='font-size:16px;'><input type="text" class="price number" name="net_total_vat" id="net_total_vat" value="0" onKeyUp="subtotalprice();" readonly="1" /></td>
                </tr>

                <tr>
                    <td align='right'></td>
                    <td align='right'></td>
                    <td colspan=4 align='right'><b>Total Cost:</b></td>
                    <td align=right style='font-size:16px;'>&nbsp;</td>
                    <td align=right style='font-size:16px;'><b><input type="text" class="total_price number" name="netsubtotal" id="netsubtotal" value="<?php echo $grand_total; ?>" /></b></td>
                </tr>

            </table>

            <table width="100%">
                <tr>
                    <td>

                        <textarea id="w_toc" name="w_toc"  rows="40" cols="110" ><?php echo html_entity_decode($toc); ?></textarea>
                        <script type="text/javascript">CKEDITOR.replace('w_toc');</script>
                    </td>
                </tr>                                                                           
                <tr>
                    <td>Include General Terms &amp; Condition: 
                        <input type="checkbox" name="checkbox" value="checkbox" checked="checked" />
                    </td>
                </tr>
            </table>

            Expected Delivery Date: <input type='text' name="delivery_date" value="<?php echo $delivery_date; ?>"/>
            <br/>
            <input type="submit" class="button" value="Create PO" name="confirm"/> 
            <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="id"/>
            <input type="hidden" name="orderids" value="<?php echo $orderids; ?>" />
            <input type="hidden" name="supplierID" value="<?php echo $supplierID; ?>" />
            <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>" />
            <input type="hidden" name="comparisonid" value="<?php echo $comparisonid; ?>" />
        </form>
    </div>
</div>
<?php include("../body/footer.php"); ?>