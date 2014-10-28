<?php
include '../lib/DbManager.php';
include "../lib/ibrahimconvert.php";

$poid = getParam("poid");
$orderids = getParam("orderids");
$quantity = getParam("quantity");
$unite_price = getParam("unite_price");
$date = date("Y-m-d");

$pr_rec = find("select pr.purchase_order_id, pr.order_no, pr.purchase_order_status, pr.comparison_id, 
pr.supplier_id, pr.order_date, pr.supp_ref, pr.orderids, pr.created_by, 
pr.branch_dept_id, pr_i.product_id, s.SUPPLIER_NAME, d.DESIGNATION_NAME
from purchase_order pr
left join purchase_order_details pr_i on pr.purchase_order_id=pr_i.purchase_order_id
LEFT JOIN supplier s ON s.SUPPLIER_ID=pr.supplier_id
LEFT JOIN employee e ON e.EMPLOYEE_ID=pr.created_by
LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
where pr.purchase_order_id='$poid'");

$orderids = explode(",", $pr_rec->orderids);
$createdby = $pr_rec->created_by;

$comparisonid = $pr_rec->comparisonid;
$supplierID = $pr_rec->supplierid;

//--------------------------

$max_pruchase = findValue("select max(purchase_order_id) as poid from purchase_order");
$q = mysql_query("Select * from pr_approval where appid = '{$_GET['id']}'") or die("Select error " . mysql_error());
$d = mysql_fetch_object($q);

$query_pro = query("SELECT pr.PRODUCT_NAME,
		  prc.qty,
		  prc.discount,
		  po.discount as overall_discount,
		  po.vat,
		  prc.purchase_order_id,
		  (select sum(deliv.quantity) as deliverd from purchase_order_delivery deliv
		  where deliv.productid=prc.product_id and deliv.poid=prc.purchase_order_id group by deliv.productid, deliv.poid) as deliverd,
		  prc.unit_price,
		  prc.product_id,
		  (prc.qty*prc.unit_price) as total
		  FROM purchase_order_details prc
		  left join purchase_order po on po.purchase_order_id=prc.purchase_order_id
		  left join product pr on pr.PRODUCT_ID=prc.product_id
		   where prc.purchase_order_id='$poid' group by prc.product_id");

include("../body/header.php");

$table_header = "<table  class='ui-state-default' width='100%'>
                        <thead>
                                <th>SL.</th>
                                <th>Product</th>
                                <th align='center'>Orderd Quantity</th>
                                <th align='right'>Unit Price</th>
                                <th align='right'>Disc(%)</th>
                                <th align='right'>Disc Amount </th>
                                <th align='right'>Total Prices</th>
                        </thead>";




$grand_total = 0;
while ($rec = fetch_object($query_pro)) {
    $overall_discount = $rec->overall_discount;
    $vat = $rec->vat;
    $remaining = $rec->quantity - $rec->deliverd;
    $x++;
    $total_discount = $rec->total * ($rec->discount / 100);
    $total = $rec->total - $total_discount;
    ?>
    <input type='hidden' name='chkproduct00[]' value='<?php echo $rec->productid . '~' . $remaining; ?>'>
    <input type='hidden' name='cancel_poid' value='<?php echo $rec->poid; ?>'>  

    <?php
    $table_list = $table_list . "<tr>
				<td align='left' class='sn' >$x.</td>
				<td>$rec->PRODUCT_NAME</td>
				<td align='center'>$rec->qty</td>
				<input type='hidden' name='quantity' value='$rec->qty' />
				<td align='right'>" . number_format($rec->unit_price, 2, '.', ',') . "</td>
				<input type='hidden' name='unite_price' value='$rec->unite_price' />
				
				<td align='right'>" . number_format($rec->discount, 2, '.', ',') . "</td>
				<td align='right'>" . number_format($total_discount, 2, '.', ',') . "</td>
				<td align='right'>" . number_format($total, 2, '.', ',') . "</td>
			</tr>";
    $grand_total +=$total;
}

$total_discount = $grand_total * ($overall_discount / 100);
$dis_less_amount = $grand_total - $total_discount;
$total_vat = $dis_less_amount * ($vat / 100);
$net_grand_total = formatMoney(($grand_total - $total_discount) + $total_vat);

$convert = new Ibiconvert();
//echo "My convertion : " . $convert->val($values) . "<br>";

list($main, $decimal) = explode(".", $net_grand_total);
//
$tk = $convert->val($main) . " taka ";
if ($decimal != "") {
    $decimal = $convert->val($decimal);
    $decimal = "and $decimal paisa ";
}

//$vat = 15;
//$nettotal = $grand_total + ($grand_total * $vat)/100;

$table_list = $table_list . "<tr >
					<td colspan='6' align='right' style='border-top:2px solid #000;'>
						<b> Amount:</b>
					</td>
					<td align='right' style='border-top:2px solid #000;'>
						<b>" . formatMoney($grand_total) . "</b>
				</td>
				</tr>

				
				<tr >
					<td colspan=6  align=right >
						<b>Overall Disc($overall_discount %):</b>
					</td>
					<td align=right >
						<b>" . formatMoney($total_discount) . "</b>
					</td>
				</tr>
				<tr >
					<td colspan=6  align=right >
						<b>VAT:($vat %)</b>
					</td>
					<td align=right >
						<b>" . formatMoney($total_vat) . "</b>
					</td>
				</tr>
				<tr >
					<td colspan=6  align=right >
						<b>Total Cost:</b>
					</td>
					<td align=right >
						<b>" . $net_grand_total . "</b>
					</td>
				</tr>
				<tr >
					<td colspan=7  align=right style='font-size:16px;'>
						<b>Tk. (In word) :</b>
					" . $tk . $decimal . "only"
        . "</b>
					</td>
				</tr>
				
			
				
				
				</table>";



$product_list = $table_header . $table_list . $table_footer;


$qq = mysql_query("Select * from po_templates where id = 4");
$dd = mysql_fetch_object($qq);

$q_supp = mysql_query("SELECT SUPPLIER_NAME, SUPPLIER_ADDRESS FROM supplier where SUPPLIER_ID = '$supplierID'");
$d_supp = mysql_fetch_object($q_supp);

$supplier_name = $d_supp->name;
$supplier_contact = $d_supp->contact;
$supplier_address = $d_supp->streetaddress . " <br>" . $d_supp->city . "-" . $d_supp->zipcode;
$date = date("D, d-M-Y");

if ($dd->toc_id != "") {
    $q_toc = mysql_query("Select tcc, gr_tcc, forwarding_text from purchaseorder_tcc where poid = '$poid'") or die(mysql_error());
    $d_toc = mysql_fetch_object($q_toc);
    $toc = $d_toc->tcc;
    $forwarding_text = $d_toc->forwarding_text;
} else {
    $toc = "";
}



$from = array("&lt;::product_list::&gt;", "&lt;::supplier_name::&gt;", "&lt;::supplier_contact::&gt;", "&lt;::supplier_address::&gt;", "&lt;::date::&gt;", "&lt;::terms::&gt;");
$change = array($product_list, $supplier_name, $supplier_contact, $supplier_address, $date, $toc);


$body = str_replace($from, $change, $dd->w_body);
?>
<div class="easyui-layout" style="width:1100px; margin: auto; height:1800px;">  
    <div data-options="region:'center'" Title='Purchase Order Details' style="padding: 10px 10px; background-color:white; "> 

        <form action="" method="post">
            <table class="table">

                <tr>
                    <td>
                        To<br />
                        <?php
                        echo $pr_rec->SUPPLIER_NAME . "<br />";
                        echo $pr_rec->SUPPLIER_ADDRESS . "<br />";
                        ?>
                    </td>
                    <td width="29%" valign="top" align="right"> WO/PO No : <?php echo $pr_rec->po_no; ?><br />

                        Date : <?php echo $pr_rec->order_date; ?><br />
                        Supplier Ref : <?php echo $pr_rec->supp_ref; ?><br />	</td>
                </tr>
            </table>

            <?php echo $product_list; ?>
            <tr>
                <td colspan="2">
                    <?php
                    $myFile = $toc;
                    $fh = fopen($myFile, "r");
                    $theData = fread($fh, filesize($myFile));
                    fclose($fh);
                    $test = html_entity_decode($theData);
                    echo $test;
                    ?>
                </td>
            </tr>
            </table>
            
            
            <table>
                <tr>
                    <td width="31%">For and on the behalf of </td>
                    <td width="31%">&nbsp;</td>
                    <td width="38%">&nbsp;</td>
                </tr>
                <tr>
                    <td>The City Bank Ltd. </td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>___________________________________</td>
                    <td>&nbsp;</td>
                    <td>_________________________________</td>
                </tr>
                <tr>
                    <td>
                        <?php
                        if ($grand_total <= 500000) {
                            $fst_person = $pr_rec->DESIGNATION_NAME;
                            $snd_person = "Head of Procurement";
                        } else {
                            $fst_person = "Head of Procurement";
                            $snd_person = "Member Procurement";
                        }
                        echo $fst_person;
                        ?>
                    </td>
                    <td>&nbsp;</td>
                    <td><?php echo $snd_person; ?></td>
                </tr>
            </table>		  

            <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="id"/>
            <input type="hidden" name="orderids" value="<?php echo $orderids; ?>" />
            <input type="hidden" name="supplierID" value="<?php echo $supplierID; ?>" />
            <input type="hidden" name="comparisonid" value="<?php echo $comparisonid; ?>" />
            <input type="submit" name="Submit" value="Close this window" onClick="window.close();">
        </form>

    </div>
</div>
<?php include("../body/footer.php"); ?>