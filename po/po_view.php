<?php
include '../lib/DbManager.php';
include "../lib/ibrahimconvert.php";

$poid = getParam("poid");
$quantity = getParam("quantity");
$unite_price = getParam("unite_price");
$date = date("Y-m-d");

$pr_rec = find("SELECT pr.purchase_order_id, pr.order_no, pr.purchase_order_status, pr.comparison_id, pr.supplier_id, 
pr.order_date, pr.supp_ref, pr.orderids, pr.created_by, pr.branch_dept_id, pr.supp_ref, pr_i.product_id,
pcq.requisition_id

FROM purchase_order pr
left join purchase_order_details pr_i on pr.purchase_order_id=pr_i.purchase_order_id
LEFT JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pr.comparison_id
where pr.purchase_order_id='$poid'");

if ($pr_rec->purchase_order_status == 3) {
    $action = "add_suppliers2.php";
} else {
    $action = "";
}
$orderids = explode(",", $pr_rec->order_no);

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
        sql("UPDATE purchase_order SET purchase_order_status=2 WHERE purchase_order_id='$poid';");
        $query = query("SELECT product_id FROM purchase_order_details WHERE purchase_order_id='$poid'");
        while ($rec = fetch_object($query)) {

            $sql_req = "SELECT requisition_id, product_id 
            FROM price_comparison_pro_req_qty pcq
            INNER JOIN price_comparison_details pcd ON pcd.comparison_id=pcq.price_comparison_id
            WHERE pcq.price_comparison_id='$pr_rec->comparison_id' AND selected=1";

            $sql_req_result = query($sql_req);

            while ($row = mysql_fetch_object($sql_req_result)) {
                sql("UPDATE requisition_details SET STATUS_APP_LEVEL=2 WHERE STATUS_APP_LEVEL=1 AND PRODUCT_ID='$rec->product_id' and REQUISITION_ID = '$row->requisition_id'");
            }
        }
    } else if (isset($cancell)) {
        sql("UPDATE purchase_order set purchase_order_status=3 where purchase_order_id='$poid'");
    } else if (isset($deliverd)) {
        sql("UPDATE purchase_order set purchase_order_status=4 where purchase_order_id='$poid'");
        $query = query("SELECT product_id FROM purchase_order_details WHERE purchase_order_id='$poid'");
        while ($rec = fetch_object($query)) {

            $sql_req = "SELECT requisition_id, product_id 
            FROM price_comparison_pro_req_qty pcq
            INNER JOIN price_comparison_details pcd ON pcd.comparison_id=pcq.price_comparison_id
            WHERE pcq.price_comparison_id='$pr_rec->comparison_id' AND selected=1";

            $sql_req_result = query($sql_req);

            while ($row = mysql_fetch_object($sql_req_result)) {
                sql("update requisition_details set STATUS_APP_LEVEL=3 where STATUS_APP_LEVEL=2 and DETAILS_STATUS=3 and PRODUCT_ID='$rec->product_id' and REQUISITION_ID = '$row->requisition_id'");
                sql("UPDATE salesorder set REQUISITION_STATUS_ID=3 where REQUISITION_ID='$row->requisition_id'");
            }
        }
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload();</script>";
    echo "<script type='text/javascript'>window.close();</script>";
}

$comparisonid = $pr_rec->comparisonid;
$supplierID = $pr_rec->supplierid;

//--------------------------


include("../body/header.php");
?>

<div class="panel-header">Purchase Order Details</div>
<div style="margin: auto; background: white; padding: 0px 20px;">  

        <form action="<?php echo $action; ?>" method="post">
            <?php
            $qq = query("Select * from po_templates where id = 4");
            $dd = fetch_object($qq);

            $q_supp = query("SELECT SUPPLIER_ADDRESS, SUPPLIER_NAME FROM supplier WHERE SUPPLIER_ID = '$supplierID'");
            $d_supp = fetch_object($q_supp);

            $supplier_name = $d_supp->SUPPLIER_NAME;
            //$supplier_contact = $d_supp->contact;
            $supplier_address = $d_supp->SUPPLIER_ADDRESS;
            $date = date("D, d-M-Y");

            if ($dd->toc_id != "") {
                $q_toc = mysql_query("Select tcc, gr_tcc, forwarding_text from purchaseorder_tcc where poid = '$poid'");
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


            <table class="table">
                <tr>
                    <td width="200" valign="top"><img src="../public/images/CityBank.png" width="100" height="50"/></td>
                    <td></td>
                    <td width="200" valign="top"><b>Prime Bank Center</b><br /> 136, Gulshan Avenue, Gulshan-2, <br />Dhaka-1212, Bangladesh<br />Web: www.thecitybank.com.bd</td>
                </tr>
            
                <tr>
                    <td>To, <br /><?php echo $supplier_name . "<br />" . $supplier_address . "<br />";?></td>
                    <td></td>
                    <td width="200" valign="top">WO/PO No : <?php echo $pr_rec->po_no; ?><br />

                        Date : <?php echo $pr_rec->orderdate; ?><br />
                        Supplier Ref : <?php echo $pr_rec->supp_ref; ?><br />
                        PR : <?php
                        $order_num = 0;
                        foreach ($orderids as $key => $val) {

                            echo "<a href='../manage_product/reco_details.php?reco_id=$pr_rec->requisition_id&productid=$pr_rec->productid' target='_blank'>$val</a>, ";

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
                    <td colspan="3" style="padding:2px;"><?php echo html_entity_decode($forwarding_text); ?></td>
                </tr>
            </table>

            <table width='100%'  class="ui-state-default">
                <thead>
                <th>SL.</th>
                <th>Product</th>
                <th align='center'>Delivered Quantity</th>
                <th align='right'>Ordered Quantity</th>
                <th align='right'>Unit Price</th>
                <th align='right'>Disc(%)</th>
                <th align='right'>Disc Amount </th>
                <th align='right'>Total Prices</th>
                </thead>

                <?php
                $query_pro = query("SELECT pr.PRODUCT_NAME,
                            prc.product_id, AT_ACTUAL,
                            sum(prc.qty) as quantity,
                            prc.discount,
                            po.discount as overall_discount,
                            po.vat,
                            prc.purchase_order_id,
                            (
                                SELECT sum(deliv.quantity) as deliverd 
                                FROM purchase_order_delivery deliv
                                WHERE deliv.productid=prc.product_id and deliv.poid=prc.purchase_order_id 
                                group by deliv.productid, deliv.poid
                            ) as deliverd,
                            prc.unit_price, prc.product_id,
                            sum(prc.qty*prc.unit_price) as total
                            FROM purchase_order_details prc
                            left join purchase_order po on po.purchase_order_id=prc.purchase_order_id
                            left join product pr on pr.PRODUCT_ID=prc.product_id
                            WHERE prc.purchase_order_id='$poid' group by prc.product_id");

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

                    <tr>
                        <td class='sn'><?php echo $x . "."; ?></td>
                        <td><?php echo $rec->PRODUCT_NAME; ?></td>
                        <td align='center'><?php echo $rec->deliverd; ?></td>
                        <td align='center'><?php
                            if ($rec->AT_ACTUAL == 1 && $rec->quantity == 0) {
                                ?>
                                <a href="at_actual_adjust.php?poid=<?php echo $poid; ?>&productid=<?php echo $rec->productid; ?>"  target="_blank">At Actual</a> 
                                <?php
                            } else {
                                echo $rec->quantity;
                            }
                            ?>
                        </td>
                    <input type='hidden' name='quantity' value='<?php echo $rec->quantity; ?>' />
                    <td align='right'><?php echo number_format($rec->unit_price, 2, '.', ','); ?></td>
                    <input type='hidden' name='unite_price' value='<?php echo $rec->unit_price; ?>' />

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
                        <b>Overall Disc (<?php echo formatMoney($overall_discount); ?> %):</b>
                    </td>
                    <td align=right >
                        <b><?php echo number_format($total_discount, 2, '.', ','); ?></b>
                    </td>
                </tr>
                <tr >
                    <td colspan=7  align='right' >
                        <b>VAT (<?php echo formatMoney($vat); ?> %):</b>
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
                        <?php echo $tk . $paisa . "only"; ?>

                        </b>
                    </td>
                </tr>
            </table>
            <div style="padding: 10px;">

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



            </div>

            <div class="footerSection">

<?php
//echo $pr_rec->purchase_order_status . ' ' . $UserLevelId;
if (($pr_rec->purchase_order_status == 1) && ($UserLevelId == 6)) {
    ?>
                    <input type="submit" class="button" name="approved" value="Finalized" />
                    <input type="submit" class="button" name="cancell" value="Cancel" />

    <?php
} else if ($pr_rec->purchase_order_status == 2 && $UserLevelId == 5) {
    ?>
                    <button type="submit" class="button" name="deliverd">Delivered to Supplier</button>
                    <?php
                } else if ($pr_rec->purchase_order_status > 2 && $UserLevelId == 5) {
                    ?>
                    <input type="button" class="button" value="Print" onclick="window.print();">
                    <a class="button" href="po_print.php?poid=<?php echo $poid; ?>" target='_blank'>Print View</a>
                    <?php
                    if ($pr_rec->purchase_order_status <= 6 && $UserLevelId == 5) {
                        ?>
                        <a class="button" name="Submit" href='po_product_delivery.php?poid=<?php echo $poid; ?>'>Product delivery</a>

                        <?php
                    }
                }
                ?>
                <a href="workorders_list.php" class="button">Back</a>
            </div>


            <input type="hidden" value="<?php echo $_GET["id"]; ?>" name="id"/>
            <input type="hidden" name="supplierID" value="<?php echo $supplierID; ?>" />
            <input type="hidden" name="comparisonid" value="<?php echo $comparisonid; ?>" />
            <input type="hidden" name="parent_url" id="parent_url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">

        </form>
    </div>
<?php include("../body/footer.php"); ?>