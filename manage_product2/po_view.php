<?php
include('include.php');
include "../include/ibrahimconvert.php";

$poid = getParam("poid");
$orderids = getParam("orderids");
$quantity = getParam("quantity");
$unite_price = getParam("unite_price");
$date = date("Y-m-d");

$pr_rec = find("select pr.poid, pr.po_no, pr.status, pr.comparisonid, pr.supplierid, pr.orderdate, pr.supp_ref, pr.orderids, pr.createdby, pr.branch_dept_id, pr.supp_ref, pr_i.productid  from purchaseorder pr
left join purchaseorder_item pr_i on pr.poid=pr_i.poid
where pr.poid='$poid'");

if ($pr_rec->status == 3) {
    $action = "add_suppliers2.php";
} else {
    $action = "";
}
$orderids = explode(",", $pr_rec->orderids);

$approved = getParam('approved');
$return_to = getParam('return_to');
$save = getParam('save');
$cancell = getParam('cancell');
$deliverd = getParam('deliverd');
$selected = getParam('selected');
if ($selected == 0000) {
    $selected = "";
}
if (isset($approved) || isset($cancell) || isset($deliverd)) {
    if (isset($approved)) {
        sql("UPDATE purchaseorder set status=2 where poid='$poid'");
        $query = query("select productid from purchaseorder_item where poid='$poid'");
        while ($rec = fetch($query)) {
            foreach ($orderids as $key_item => $value_item) {
                sql("update salesorder_item set status_app_level=2 where status_app_level=1 and productid='$rec->productid' and orderid = '$value_item'");
            }
        }
    } else if (isset($cancell)) {
        sql("UPDATE purchaseorder set status=3 where poid='$poid'");
    } else if (isset($deliverd)) {
        sql("UPDATE purchaseorder set status=4 where poid='$poid'");
        $query = query("select productid from purchaseorder_item where poid='$poid'");
        while ($rec = fetch($query)) {
            foreach ($orderids as $key_item => $value_item) {
                sql("update salesorder_item set status_app_level=3 where status_app_level=2 and status=3 and productid='$rec->productid' and orderid = '$value_item'");

                sql("UPDATE salesorder set status=3 where orderid='$value_item'");
            }
        }
    }
    ?>
    <script type="text/javascript">
        window.location = "workorders_list.php"
    </script>
    <?php
}

$comparisonid = $pr_rec->comparisonid;
$supplierID = $pr_rec->supplierid;

//--------------------------

$max_pruchase = findValue("select max(poid) as poid from purchaseorder");

$title = "City bank ";
$inlude_datebox = true;
include("../body/header.php");
?>


<h2 style="color:#000066; ">Purchase Order Details</h2><br />

<form action="<?php echo $action; ?>" method="post">
    <?php
    $q = mysql_query("Select * from pr_approval where appid = '{$_GET['id']}'") or die("Select error " . mysql_error());
    $d = mysql_fetch_object($q);

    $product_list = $table_header . $table_list . $table_footer;


    $qq = mysql_query("Select * from po_templates where id = 4");
    $dd = mysql_fetch_object($qq);

    $q_supp = mysql_query("Select contact, name, streetaddress, city, zipcode from supplier where supplierid = '$supplierID'") or die("Select error 2 " . mysql_error());
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


    <table width="100%" cellpadding="3" cellspacing="2" border='0'>
        <tr>
            <td width="80%" valign="top"><img src="../public/images/logo.gif" width="120" height="80"/></td>
            <td width="20%" valign="top"><b>City Bank Center</b><br /> 136, Gulshan Avenue, Gulshan-2, <br />Dhaka-1212, Bangladesh<br />Web: www.thecitybank.com.bd</td>
        </tr>

        <tr>
            <td valign="top" colspan="2" >
                <table width="100%" border="0" cellpadding="" border='0'>
                    <tr>
                        <td valign="top" width="75%">To<br />
                            <?php
                            echo $supplier_name . "<br />";
                            echo $supplier_address . "<br />";
                            ?>
                        </td>
                        <td width="200" valign="top">WO/PO No : <?php echo $pr_rec->po_no; ?><br />

                            Date : <?php echo $pr_rec->orderdate; ?><br />
                            Supplier Ref : <?php echo $pr_rec->supp_ref; ?><br />
                            PR : <?php
                            $order_num = 0;
                            foreach ($orderids as $key => $val) {
                                $count++;
                                if ($count > 1) {
                                    $orderids = $orderids . ',' . $val;
                                    echo ",<a href='../requisition/reco_details.php?reco_id=$val&productid=$pr_rec->productid' target='_blank'>" . reco_no($val) . "</a>";
                                } else {
                                    $orderids = $val;
                                    echo "<a href='../requisition/reco_details.php?reco_id=$val&productid=$pr_rec->productid' target='_blank' >" . reco_no($val) . "</a>";
                                }

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
                        <td colspan="2" style="padding:2px;"><?php
                            echo html_entity_decode($forwarding_text);
                            ?>
                        </td>
                    </tr>
                </table>
                <table width='100%'  id='hor-minimalist-b' border='0'>
                    <thead>
                    <th>SL.</th>
                    <th>Product</th>
                    <th align='center'>Deliverd Quantity</th>
                    <th align='center'>Orderd Quantity</th>
                    <th align='right'>Unit Price</th>
                    <th align='right'>Disc(%)</th>
                    <th align='right'>Disc Amount </th>
                    <th align='right'>Total Prices</th>
                    </thead>

                    <?php
                    $query_pro = query("select 
			pr.model,
			prc.productid,
		  sum(prc.quantity) as quantity,
		  prc.discount,
		  po.discount as overall_discount,
		  po.vat,
		  prc.poid,
		  (select sum(deliv.quantity) as deliverd from purchaseorder_item_delivery deliv
		  where deliv.productid=prc.productid and deliv.poid=prc.poid group by deliv.productid, deliv.poid) as deliverd,
		  prc.unitprice,
		  prc.productid,
		  sum(prc.quantity*prc.unitprice) as total
		  from 
		  purchaseorder_item prc
		  left join purchaseorder po on po.poid=prc.poid
		  left join product pr on pr.productid=prc.productid
		   where prc.poid='$poid' group by prc.productid");

                    $grand_total = 0;
                    while ($rec = fetch($query_pro)) {
                        $overall_discount = $rec->overall_discount;
                        $vat = $rec->vat;
                        $remaining = $rec->quantity - $rec->deliverd;
                        $x++;
                        $total_discount = $rec->total * ($rec->discount / 100);
                        $total = $rec->total - $total_discount;
                        $at_actual = findValue("select at_actual from product where productid=$rec->productid");
                        ?>
                        <input type='hidden' name='chkproduct00[]' value='<?php echo $rec->productid . '~' . $remaining; ?>'>
                        <input type='hidden' name='cancel_poid' value='<?php echo $rec->poid; ?>'>  

                        <tr>
                            <td class='sn'><?php echo $x . "."; ?></td>
                            <td><?php echo $rec->model; ?></td>
                            <td align='center'><?php echo $rec->deliverd; ?></td>
                            <td align='center'><?php
                                if ($at_actual == 1 && $rec->quantity == 0) {
                                    ?>
                                    <a href="#"  onclick="window.open('at_actual_adjust.php?poid=<?php echo $poid; ?>&productid=<?php echo $rec->productid; ?>', 'popup', 'width=350,height=400,scrollbars=no,scrollbars=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=300,top=110');
                        return false" target="_blank">At Actual</a> 
                                       <?php
                                   } else {
                                       echo $rec->quantity;
                                   }
                                   ?></td>
                        <input type='hidden' name='quantity' value='<?php echo $rec->quantity; ?>' />
                        <td align='right'><?php echo number_format($rec->unitprice, 2, '.', ','); ?></td>
                        <input type='hidden' name='unite_price' value='<?php echo $rec->unite_price; ?>' />

                        <td align='right'><?php echo number_format($rec->discount, 2, '.', ','); ?></td>
                        <td align='right'><?php echo number_format($total_discount, 2, '.', ','); ?></td>
                        <td align='right'><?php echo number_format($total, 2, '.', ','); ?></td>
            </tr>
            <?php
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
//echo $decimal.'--';
        if ($decimal > 0) {
            $decimal = $convert->val($decimal);
            $paisa = "and $decimal paisa ";
        }
//$vat = 15;
//$nettotal = $grand_total + ($grand_total * $vat)/100;
        ?>

        <tr >
            <td colspan=7 align=right style='border-top:2px solid #000;'>
                <b> Amount:</b>
            </td>
            <td align=right style='border-top:2px solid #000;'>
                <b><?php echo number_format($grand_total, 2, '.', ','); ?></b>
            </td>
        </tr>


        <tr >
            <td colspan=7  align=right >
                <b>Overall Disc(<?php echo $overall_discount; ?> %):</b>
            </td>
            <td align=right >
                <b><?php echo number_format($total_discount, 2, '.', ','); ?></b>
            </td>
        </tr>
        <tr >
            <td colspan=7  align=right >
                <b>VAT:(<?php echo $vat; ?> %)</b>
            </td>
            <td align=right >
                <b><?php echo number_format($total_vat, 2, '.', ','); ?></b>
            </td>
        </tr>
        <tr >
            <td colspan=7  align=right >
                <b>Total Cost:</b>
            </td>
            <td align=right >
                <b><?php echo number_format($net_grand_total, 2, '.', ','); ?></b>
            </td>
        </tr>
        <tr >
            <td colspan=8  align=right style='font-size:16px;'>
                <b>Tk. (In word) :</b>
                <?php echo $tk . $paisa . "only";?>

                </b>
            </td>
        </tr>




    </table>



    <table>

        <tr>
            <td colspan="2">
                <?php
                $myFile = $toc;

//$theData = array_map('\r\n', file($myFile));
                $fh = fopen($myFile, "r");
                $theData = fread($fh, filesize($myFile));
                fclose($fh);
//echo $theData;
//$theData = rtrim($theData, "\r\n") . PHP_EOL;
//$theData = str_replace("\r\n", " ", $theData);
//$theData = ($theData, "\r\n") . PHP_EOL;
// echo htmlspecialchars(html_entity_decode($theData)); 
//echo html_entity_decode($theData); 
                $test = html_entity_decode($theData);

                echo $test;
//echo nl2br($test);
//echo nl2br($theData);
//  echo "-----";
                ?>
            </td>
        </tr>






        </td>

        <td>&nbsp;</td>
        </tr>



    </table>




    <div class="footerSection">

        <?php
        if (($pr_rec->status == 1) && ($level_designation == 2)) {
            ?>
            <input type="submit" class="button" name="approved" value="Finalized" />
            <?php
        } else if ($pr_rec->status == 2) {
            ?>
            <button type="submit" class="button" name="deliverd">Delivered to Supplier</button>
            <?php
        } else if ($pr_rec->status > 2) {
            ?>
            <input type="button" class="button" value="Print" onclick="window.print()">
            <a class="button" href="po_print.php?poid=<?php echo $poid; ?>" target='_blank'>Print View</a>
            <?php
            if ($pr_rec->status < 6) {
                ?>
                <input type="submit" class="button" name="Submit" value="Product delivery"  onclick="window.open('po_product_delivery.php?poid=<?php echo $poid; ?>', 'popup', 'width=860,height=500,scrollbars=no,scrollbars=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=300,top=110');
                return false"/>
                       <?php
                   }
               }
               ?>
        <input type="submit" class="button" name="cancell" value="Cancel" />

        <?php
        if ($pr_rec->status == 3) {
            ?>
            <input type="submit" name="btnsupplier" class="button" value="Create Comparative Statement" />
        <?php } ?>

    </div>


    <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="id"/>
    <input type="hidden" name="orderids" value="<?php echo $orderids; ?>" />
    <input type="hidden" name="supplierID" value="<?php echo $supplierID; ?>" />
    <input type="hidden" name="comparisonid" value="<?php echo $comparisonid; ?>" />
    <input type="hidden" name="parent_url" id="parent_url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">

</form>
<?php include("../body/footer.php"); ?>