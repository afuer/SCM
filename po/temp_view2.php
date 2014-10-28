<?php
include('../lib/DbManager.php');
//include "../lib/ibrahimconvert.php";
include("../body/header.php");
$reorderpo = getParam('reorderpo');
$chkproduct00 = $_SESSION['chkproduct00'];
$orderids_list = getParam('orderids_list');
$delivery_date = getParam('delivery_date');

$delivery_date = $delivery_date == "" ? date("Y-m-d") : $delivery_date;

$req_result = query("select requisition_id from price_comparison_pro_req_qty where price_comparison_id='$comparisonid'");

while ($row = fetch_object($req_result)) {

    $orderid_list.=$row->requisition_id . ',';
}

$orderid_list = substr($orderid_list, 0, -1);
$orderid_list = $orderid_list == '' ? 0 : $orderid_list;
$office_types = "";
$branch_dept_ids = "";
$branch_dept = query("SELECT OFFICE_TYPE_ID, BRANCH_DEPT_ID
                FROM requisition 
                WHERE REQUISITION_ID IN ($orderid_list)");

while ($rec_2 = fetch_object($branch_dept)) {

    if ($$office_types == "") {
        $office_types = $rec_2->OFFICE_TYPE_ID;
        $branch_dept_ids = $rec_2->BRANCH_DEPT_ID;
    }
}



$record = find("select comparisonid, po_no, supplierid, supp_ref  from purchaseorder where po_no ='$reorderpo'");
$supplierID = $record->supplierid;
$comparisonid = $record->comparisonid;

$quantity = getParam("quantity");
$unite_price = getParam("unite_price");
$delivery_date = getParam('delivery_date');
$toc_con = getParam('toc_con');
$w_toc = getParam('w_toc');
$forwarding_text = getParam('forwarding_text');
$date = date("Y-m-d");


$office_types = "";
$branch_dept_ids = "";
//echo $orderids_list.'-------';
if ($orderids_list) {
    $branch_dept = query("select office_type, branch_dept_id from salesorder where orderid in ($orderids_list)");
    while ($rec_2 = fetch_object($branch_dept)) {
        if ($$office_types == "") {
            $office_types = $rec_2->office_type;
            $branch_dept_ids = $rec_2->branch_dept_id;
        } else {
            $office_types = implode(",", $rec_2->office_type);
            $branch_dept_ids = implode(",", $rec_2->branch_dept_id);
        }
    }
}



//--------------------------

$max_pruchase = findValue("select max(poid) as poid from purchaseorder");
$po_id = $max_pruchase + 1;


$qq = query("Select * from po_templates where id = 4");
$dd = fetch_object($qq);

$q_supp = query("SELECT SUPPLIER_ADDRESS, SUPPLIER_NAME
FROM supplier WHERE SUPPLIER_ID = '$supplierID'");
$d_supp = mysql_fetch_object($q_supp);

$supplier_name = $d_supp->SUPPLIER_NAME;
$supplier_contact = $d_supp->SUPPLIER_ADDRESS;
$supplier_address = $d_supp->SUPPLIER_ADDRESS;

if ($dd->toc_id != "") {
    $q_toc = mysql_query("Select w_toc from po_toc where id = $dd->toc_id") or die(mysql_error());
    $d_toc = mysql_fetch_object($q_toc);
    $toc = $d_toc->w_toc;
} else {
    $toc = "";
}



$from = array("&lt;::product_list::&gt;", "&lt;::supplier_name::&gt;", "&lt;::supplier_contact::&gt;", "&lt;::supplier_address::&gt;", "&lt;::date::&gt;", "&lt;::terms::&gt;");
$change = array($product_list, $supplier_name, $supplier_contact, $supplier_address, $date, $toc);


$body = str_replace($from, $change, $dd->w_body);

$confirm = getParam('confirm');
if ($confirm == 'confirm') {



    $orderids_list2 = array_unique($orderid);
    $orderids_list = implode(",", $orderids_list2);

    $net_disc_rate = getParam('net_disc_rate');
    $net_total_disc = getParam('net_total_disc');
    $vat_rate = getParam('vat_rate');
    $net_total_vat = getParam('net_total_vat');

    $today = date("Y-m-d");

    foreach ($chkproduct00 as $key3 => $val3) {
        list($productid, $quantity) = explode("~", $val3);
        $x++;
    }

    $re_sql = "select unitprice, poi.poid, poi.discount, po.supp_ref from purchaseorder_item poi 
				left join purchaseorder po on po.poid = poi.poid
				where po_no=$reorderpo and productid=$productid";
    $rec = find($re_sql);
    $discount_amount = ($rec->discount / 100) * ($rec->unitprice * $quantity);
    $sub_total = $rec->unitprice * $quantity - $discount_amount;

    $sql_p = "insert into purchaseorder (
			orderdate, 
			office_type, 
			branch_dept_id, 
			comparisonid, 
			supplierid, 
			orderids, 
			createdby, 
			supp_ref,
			discount,
			total_discount,
			vat,
			total_val)
			values
			('$today', 
			'$office_types',
			'$branch_dept_ids',
			'$comparisonid', 
			'$supplierID', 
			'$orderids_list', 
			'$employeeid',  
			'$rec->supp_ref',
			'$net_disc_rate',
			'$net_total_disc',
			'$vat_rate',
			'$net_total_vat')";
    sql($sql_p);
    $poid = insert_id();

    $sql_reorder = "insert into reorder_po_info(
			orderdate, 
			master_po, 
			reorder_po, 
			rmks)
			values
			(
			'$today', 
			'$reorderpo',
			'$poid',
			'reorder')";
    sql($sql_reorder);

    $x = 0;
    foreach ($chkproduct00 as $key3 => $val3) {
        list($productid, $quantity) = explode("~", $val3);
        $x++;
        $unite_price = getParam('unite_price' . $x);
        $discount_rate = getParam('discount_rate' . $x);

        $orders2 = explode(",", $orderids_list);
        foreach ($orders2 as $key_order2 => $value_order2) {
            $data = find("select so.office_type, so.branch_dept_id, si.quantity from salesorder so
				left join salesorder_item si on si.orderid = so.orderid
				where si.orderid='$value_order2' and si.productid='$productid' and status_app_level!=3");

            $discount_rate = getParam('discount_rate' . $count);

            $productqty = findValue("select prd.quantity from 
		  price_comparison prc
		  left join price_comparison_details prd on prc.comparisonid=prd.comparison_id
		   where prc.comparisonid='$comparisonid' and prd.selected=1 and prd.supplier_id='$supplierID' group by prd.productid");


            $sql_p_det = "Insert into purchaseorder_item(poid, office_type, branch_dept_id, productid, quantity, unitprice, discount, status)
				values ($poid, '$data->office_type', '$data->branch_dept_id', '$productid', '$data->quantity', '$unite_price', '$discount_rate', 5)";

            sql($sql_p_det);

            sql("update salesorder_item set status_app_level=1, status=3 where productid='$productid' and orderid='$value_order2'");
        }
    }
    //---------------save terms & condition into folder as a html file--------
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

    $p_toc = "Insert into purchaseorder_tcc(poid, tcc, gr_tcc, forwarding_text)
			values ($poid, '$myFile', '$myFile2', '$forwarding_text')";
    sql($p_toc);
    $po_no = po_no($poid);


    sql("Update purchaseorder set po_no=$po_no where poid=$poid");
}
?>



<script type="text/javascript" src="../manage_product/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../manage_product/js/ckeditor/config.js"></script>
<h2 style="color:#000066; ">Purchase Order Details</h2>
<br />

<form action="" name="autoSumForm" method="post">
    <<table>
        <tr>
            <td colspan="2" valign="top"><img src="../public/images/PrimeBank.png" height="30" /></td>
            <td width="42%" valign="top">&nbsp;</td>
            <td width="29%" valign="top"><b>Prime Bank Center</b><br /> 
                136, Gulshan Avenue, Gulshan-2, <br>Dhaka-1212, Bangladesh<br>Web: www.thecitybank.com.bd</td>
        </tr>
    </table>
    <table width="100%" border="0">
        <tr>
            <td align="left">To<br />
                <?php
                echo $supplier_name . "<br />";
                echo $supplier_address . "<br />";
                ?>
            </td>
            <td align="right">WO/PO No : <?php echo evaluation_no($po_id); ?><br />

                Date : <?php echo $date; ?><br />
                Supplier Ref : <?php echo $reference; ?><br />
                PR : <?php
                $orders = explode(",", $orderid_list);

                $count = 0;
                $order_num = 0;
                foreach ($orders as $key_order => $value_order) {
                    echo "<a href='../manage_product/reco_details.php?reco_id=$value_order&productid=$productid' target='_blank'> " . reco_no($value_order) . "</a>";

                    $order_num++;
                    if ($order_num == 3) {
                        echo "<br />";
                        $order_num = 0;
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea name="forwarding_text" style="width: 100%; height: 100px;">Dear Mr/Mrs. <?php echo $supplier_contact; ?>
        
            We have the pleasure in submitting our quotation as requested. Please contact me if you have any queries.More text here.. Please contact me if you have any queries.More text here.sted. Please contact me if you have any queries.More text here.. Please contact me if you have any queries.More text here. sted. Please contact me if you have any queries.More text here.. Please contact me if you have any queries.More text here. 
                </textarea>

            </td>
        </tr>
    </table>

    <table width='100%'  class="ui-state-default">
        <tr>
            <th width="25">Sn</th>
            <th>Product</th>
            <th width="10%" align='center'>Quantity</th>
            <th width="13%" align='center'>Unit Price</th>
            <th width="15%" align='center'>WO Value </th>
            <th width="9%" align='center'>Discount(%)</th>
            <th width="13%" align='center'>Disc. Amount </th>
            <th width="17%" align='center'>Total Value </th>
        </tr>
        <?php
        foreach ($chkproduct00 as $key3 => $val3) {
            list($productid, $quantity) = explode("~", $val3);
            $x++;
            $table_list = $table_list;
            $re_sql = "select unitprice, poi.discount from purchaseorder_item poi 
				left join purchaseorder po on po.poid = poi.poid
				where po_no='$reorderpo' and productid='$productid'";
            $rec = find($re_sql);
            $discount_amount = ($rec->discount / 100) * ($rec->unitprice * $quantity);
            $sub_total = $rec->unitprice * $quantity - $discount_amount;
            ?>
            <tr>
                <td class='sn'><?php echo $x; ?></td>
                <td><?php echo findValue("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$productid'"); ?></td>
                <td align='center'><?php echo $quantity; ?></td>
            <input type='hidden' name='quantity' id="quantity<?php echo $x; ?>" value='<?php echo $quantity; ?>' />
            <td align='center'><input type='text' name='unite_price<?php echo $x; ?>' id="unite_price<?php echo $x; ?>" value='<?php echo $rec->unitprice; ?>' size="10" onKeyUp="totalprice<?php echo $x; ?>()" onBlur="gross_total()"/></td>
            <td align='right'>
                <input type="text" name="bill_amount" id="bill_amount<?php echo $x; ?>" value="<?php echo $rec->unitprice * $quantity; ?>" />				</td>
            <td align='center'>
                <input type="text" name="discount_rate<?php echo $x; ?>" id="discount_rate<?php echo $x; ?>" onKeyUp="totalprice<?php echo $x; ?>()" onBlur="gross_total()" value="0" size="4" />				</td>
            <td align='right'><input type="text" name="total_disc<?php echo $x; ?>"  id="total_disc<?php echo $x; ?>" readonly="1" value="<?php echo $discount_amount; ?>" size="8" /></td>
            <input type='hidden' name='unite_price' value='<?php echo $rec->unite_price; ?>' />
            <td align='right'><input type="text" name="subtotal<?php echo $x; ?>" readonly="1" value="<?php echo $sub_total; ?>"/></td>
            </tr>

            <?php
            $grand_total +=$rec->total;
        }

//$vat = 15;
//$nettotal = $grand_total + ($grand_total * $vat)/100;

        $table_list = $table_list;
        ?><tr >
            <td colspan=4  align=right style='border-top:2px solid #000;'>
                <b> Amount:</b>					</td>
            <td align=right style='border-top:2px solid #000;'><strong><?php echo formatMoney($grand_total); ?></strong></td>
            <td align=right style='border-top:2px solid #000;'>&nbsp;</td>
            <td align=right style='border-top:2px solid #000;'>&nbsp;</td>
            <td align=right style='border-top:2px solid #000;'>
                <b><input type="text" name="gross_value" value="<?php echo formatMoney($grand_total); ?>" /></b>					</td>
        </tr>
        <tr >
            <td colspan=4  align=right ><strong>Discount(%)</strong></td>
            <td align=right style='font-size:16px;'>&nbsp;</td>
            <td align=right style='font-size:16px;'>&nbsp;</td>
            <td align=right style='font-size:16px;'><input type="text" name="net_disc_rate" id="net_disc_rate" value="0" onKeyUp="subtotalprice()" size="8" /></td>
            <td align=right style='font-size:16px;'><input type="text" name="net_total_disc" id="net_total_disc" value="0" onKeyUp="subtotaldisc()"  /></td>
        </tr>
        <tr >
            <td colspan=4  align=right ><strong>VAT(%)</strong></td>
            <td align=right style='font-size:16px;'>&nbsp;</td>
            <td align=right style='font-size:16px;'>&nbsp;</td>
            <td align=right style='font-size:16px;'><input type="text" name="vat_rate" id="vat_rate" value="0" onKeyUp="subtotalprice()" size="8" /></td>
            <td align=right style='font-size:16px;'><input type="text" name="net_total_vat" id="net_total_vat" value="0" onKeyUp="subtotalprice()" readonly="1" /></td>
        </tr>

        <tr >
            <td colspan=4  align=right >
                <b>Total Cost:</b>					</td>
            <td align=right style='font-size:16px;'>&nbsp;</td>
            <td align=right style='font-size:16px;'>&nbsp;</td>
            <td align=right style='font-size:16px;'>&nbsp;</td>
            <td align=right style='font-size:16px;'>
                <b>
                <!--<input type="text" name="netsubtotal" id="netsubtotal" readonly="1" value="<?php echo $grand_total; ?>" onChange="ajaxLoader2('ajax_calculator.php?val='+this.value+'','ajax_word','<left><img src=../images/ajaxLoader.gif></left>');" />
                    -->				
                    <input type="text" name="netsubtotal" id="netsubtotal" value="<?php echo $grand_total; ?>"  />
                </b>					</td>
        </tr>
    </table>


    <table>
        <tr>
            <td>Select Template</td>
            <td><select name="templa_name" onChange="submit()">
                    <option></option>
                    <?php
                    $main_query = query("select maincategoryid, name from maincategory");
                    while ($rec_m = fetch_object($main_query)) {
                        ?>
                        <option value="<?php echo $rec_m->maincategoryid; ?>"<?php
                        if ($_REQUEST['templa_name'] == $rec_m->maincategoryid) {
                            echo "selected";
                        }
                        ?>><?php echo $rec_m->name . '&nbsp; Template'; ?></option>
                            <?php } ?>
                </select></td>
        </tr>
    </table>

    <table width='100%'>
        <tr>
            <td colspan="2">
                <textarea  id="w_toc" name="w_toc"  rows="40" cols="110" ><?php echo $toc; ?></textarea>
                <script type="text/javascript">CKEDITOR.replace('w_toc');</script>
            </td>
        </tr>                                                                           
        <tr>
            <td>Include General Terms &amp; Condition: 
                <input type="checkbox" name="checkbox" value="checkbox" checked="checked" /></td>
            <td>&nbsp;</td>
        </tr>
    </table>

    Expected Delivery Date:<input type='text' name="delivery_date" value="<?php echo $delivery_date; ?>" class="easyui-datebox"/>
    <br/>
    <button type="submit" value="confirm" name="confirm" class="button">Create PO</button>


    <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="id"/>
    <input type="hidden" name="supplierID" value="<?php echo $supplierID; ?>" />
    <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>" />
    <input type="hidden" name="comparisonid" value="<?php echo $comparisonid; ?>" />
    <input type="hidden" name="reorderpo" value="<?php echo $reorderpo; ?>">
    <input type="hidden" name="orderids_list" value="<?php echo $orderids_list; ?>" />

</form>
<?php include("../body/footer.php"); ?>