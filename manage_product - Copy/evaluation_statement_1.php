<?php
include '../lib/DbManager.php';
include("../body/header.php");


$comparison_id = getParam('comparison_id');

$date = findValue("select date from price_comparison where comparisonid ='$comparison_id'");
$approved = $_REQUEST['approved'];

if ($employ_level == 7) {
    $condition = " and com.selected=1";
}
$rec_com = find("select * from price_comparison where comparisonid='$comparison_id'");



$save = getParam('save');

$cancell = getParam('cancell');


$com_rec = find("select comparative_code, cancel_poid, createby, approved, group_item from price_comparison  where comparisonid='$comparison_id'");



$cancel_poid = $com_rec->cancel_poid;
$approved = $com_rec->approved;
$group_item = $com_rec->group_item;



if (isset($approved) || isset($save) || isset($cancell)) {

    if (isset($save)) {

        include '../requisition/manager.php';
        $change_status = ($rec_com->status == 1) ? 2 : $rec_com->status;

        $manager = new WorkFlowManager();
        $lineManager = $manager->GetLineManager($user_name);

        sql("UPDATE price_comparison set status='$change_status', PRESENT_LOCATION_ID='$lineManager->LINE_MANAGER_ID' where comparisonid='$comparison_id'");

        echo "<script type='text/javascript'>window.location = 'evaluation_statement.php?comparison_id=$comparison_id'</script>";
    } else if (isset($cancell)) {
        sql("UPDATE price_comparison set location=status-1 where comparisonid='$comparison_id'");
    }
}


$sql = "select s.SUPPLIER_NAME, 
	  com.supplier_id,
	  com.position, 
	  com.productid,
	  com.unite_price,
	  com.quantity,
	  com.unite_price*com.quantity as value,
	  com.selected	  
        from price_comparison_details com
	  left join supplier s on com.supplier_id=s.SUPPLIER_ID
	   where com.comparison_id='$comparison_id' group by com.supplier_id order by supplier_id asc";
$query_supp = query($sql);
$query_supp2 = query($sql);
$num_row = mysql_num_rows($query_supp);



$width = 600 + (120 * $num_row);
$bw = 1 * $num_row;
?>

<style type="text/css">
    table.ui-state-default tr td{border: 1px solid gray;}
</style>

<div class="easyui-layout" style="margin: auto; height:800px;">  
    <div data-options="region:'center'" Title='Comparative Statement Details' style="padding: 10px 10px; background-color:white; "> 

        <form action="" method="post">
            <input type="hidden" name="comparison_id" value="<?php echo $comparison_id; ?>"/>

            <table width="<?php echo $width; ?>px" class="table">
                <tr>
                    <td align="center" class="title"><strong>PROCUREMENT DEPARTMENT</strong></td>
                </tr>
                <tr>
                    <td align="center" class="title">The City Bank Ltd. </td>
                </tr>
                <tr>
                    <td align="center" class="title">QUOTATION EVALUATION STATEMENT </td>
                </tr>
                <?php
                if ($cancel_poid > 0) {
                    ?>
                    <tr>
                        <td align="center">Cancel PO : <?php echo evaluation_no($cancel_poid); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td align="center">Comparison ID : <?php echo $comparison_id . ',      ' . 'Date: ' . bddate($date);
                ?></td>
                </tr>
                <tr>
                    <td align="center">PR :<?php
                $sql_pri_qty_req = "SELECT requisition_id FROM price_comparison_pro_req_qty WHERE price_comparison_id='$comparison_id'";
                $orderid_result = query($sql_pri_qty_req);
                while ($row = fetch_object($orderid_result)) {
                    $reco_num = reco_no($row->requisition_id);
                    echo "<a href='reco_details.php?reco_id=$row->requisition_id&productid=$row->product_id' target='_blank'>$reco_num</a> ";
                }
                ?>
                    </td>
                </tr>
            </table>

            <table width="<?php echo $width; ?>px"  class="ui-state-default">

                <tr style="background-color:#E4E4E4">
                    <td width="5%"  class="none" rowspan="2" align="center" style="font-weight:bold">SL.</td>
                    <td width="17%"   class="none" rowspan="2" align="center" style="font-weight:bold">Item  Description </td>
                    <td width="7%" rowspan="2"  class="none" align="center" style="font-weight:bold">UoM</td>
                    <?php
                    $width = 100 / $num_row;
                    $supplier_ids = "";
                    while ($rec_supp = fetch_object($query_supp)) {
                        $count++;

                        if ($rec_supp->selected == 1) {
                            $bgcolor = "style='background-color:#CBFF97; font-weight:bold; font-size:10px; letter-spacing:1px; color:red'";
                            $selected_op = "(selected)";
                        } else {
                            $bgcolor = "";
                            $selected_op = "";
                        }
                        if ($count > 1) {
                            $supplier_ids .="," . $rec_supp->supplier_id;
                        } else {
                            $supplier_ids = $rec_supp->supplier_id;
                        }
                        ?>
                        <td colspan="3"  align="center" style="font-weight:bold" ><?php echo $rec_supp->SUPPLIER_NAME . "<br />Position:<b style='font-size:12px; color:red'> " . $rec_supp->position . "</b>"; ?></td>
                    <?php } ?>
                </tr>
                <tr style="background-color:#E4E4E4">
                    <?php
                    while ($rec_supp2 = fetch_object($query_supp2)) {
                        ?>
                        <td width="11%" align="center" style="font-weight:bold">Unit Price </td>
                        <td width="4%"  align="center" style="font-weight:bold">Qty</td>
                        <td width="11%" align="center" style="font-weight:bold" >Value (BDT) </td>
                    <?php } ?>
                </tr>
                <?php
                $cs_sql = "SELECT p.PRODUCT_NAME, ut.UNIT_TYPE_NAME, pcd.productid, pcd.supplier_id
                FROM price_comparison_details pcd 
                LEFT JOIN product p ON p.PRODUCT_ID=pcd.productid
                LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
                WHERE comparison_id='$comparison_id'";
                $query_s = query($cs_sql);
                $sl = 0;
                while ($rec_s = fetch_object($query_s)) {
                    $colspan2 = ($count * 3) + 4;
                    $sl++;
                    ?>
                    <tr style="background-color:#D5D5AA">
                        <td align='center' class="none" height="40"><?php echo $sl . "."; ?></td>
                        <td class="none"><?php echo $rec_s->PRODUCT_NAME; ?></td>
                        <td  align='center' class="none"><?php echo $rec_s->UNIT_TYPE_NAME; ?></td>
                        <?php
                        $total_vale = 0;
                        echo $sql2 = "SELECT com.selected, com.unite_price, cs_qty


                            FROM price_comparison_details com
                            INNER JOIN price_comparison_pro_req_qty pcd ON pcd.price_comparison_id=com.comparison_id
                            WHERE com.comparison_id='$comparison_id' and productid='$rec_s->productid' AND supplier_id='$rec_s->supplier_id' 
                            order by supplier_id asc";

                        $sql_query = query($sql2);
                        while ($rec_price = fetch_object($sql_query)) {
                            $total_vale += $rec_price->total;
                            if ($rec_price->selected == 1) {
                                $bgcolor = "style='background-color:#CBFF97; font-weight:bold; font-size:10px; letter-spacing:1px; color:red'";
                                $selected_op = "(selected)";
                            } else {
                                $bgcolor = "";
                                $selected_op = "";
                            }
                            ?>
                            <td align=right <?php echo $bgcolor; ?>><?php echo number_format($rec_price->unite_price, 2, '.', ','); ?></td>
                            <td align=right  <?php echo $bgcolor; ?>><span class="none"><?php echo $rec_price->cs_qty; ?></span></td>
                            <td align=right  <?php echo $bgcolor; ?>><?php echo number_format($rec_price->total, 2, '.', ','); ?></td>
                        <?php } ?>
                    </tr>
                    <?php
                    if (($UserLevelId == 2) || ($com_rec->createby == $userName)) {
                        if ($rec_com->status < 4) {
                            ?>
                            <tr>
                                <td align='left' colspan="<?php echo $colspan2; ?>">
                                    <a href="supplier_select.php?compersionid=<?php echo $comparison_id; ?>&productid=<?php echo $rec_s->productid; ?>" target="_blank" class="button" ><span class = "icon plus"></span> Select Supplier </a>

                                </td>
                            </tr>
                            <?php
                        }
                    }

                    if ($group_item == 0) {
                        ?>

                        <tr style="background-color:#E6E6F2">
                            <td colspan="<?php echo $colspan2; ?>" valign="top">&nbsp;<strong>Specification</strong> : 
                                <?php
                                if ($rec_com->status < 4) {
                                    echo "<a href='specification_change.php?comparisonid=$comparison_id;&productid=$rec_s->productid' target='_blank'>Edit</a> | <a href='pop_specification.php?supplierids=$supplier_ids&productid=$rec_s->productid&compersionid=$comparison_id' target='_blank' >Add </a>";
                                }
                                ?>	   </td>
                        </tr>
                        <?php
                        $spec_query = query("select * from price_coparison_specification where comparisonid='$comparison_id' and productid='$rec_s->productid'");
                        while ($rec_sep = fetch_object($spec_query)) {
                            $sn++;
                            ?>
                            <tr>
                                <td><?php echo $sn . "."; ?></td>
                                <td colspan="3" valign="top"><?php echo $rec_sep->condition; ?> </td>
                                <?php
                                $sql_query = query($sql);
                                while ($rec_sup = fetch_object($sql_query)) {
                                    ?>
                                    <td colspan="3"><?php echo findValue("select value from price_comparison_specefication_details where comparisonid=$comparison_id and supplier_id='$rec_sup->supplier_id' and conditionid='$rec_sep->conditionid'"); ?> </td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr style="background-color:#E6E6F2">
                            <td colspan="<?php echo $colspan2; ?>" valign="top"> <strong>Conditions</strong>:
                                <?php
                                if ($rec_com->status < 4) {
                                    ?>
                                    <a href='condition_change.php?comparisonid=<?php echo $comparison_id; ?>&productid=<?php echo $rec_s->productid; ?>' target="_blank" >Edit</a> | <a href='pop_service.php?supplierids=<?php echo $supplier_ids; ?>&productid=<?php echo $rec_s->productid; ?>&compersionid=<?php echo $comparison_id; ?>' target="_blank">Add </a> 
                                <?php } ?>	   </td>
                        </tr>
                        <?php
                        $condition_query = query("select * from price_comparison_condition where comparisonid='$comparison_id' and productid='$rec_s->productid'");
                        while ($rec_condition = fetch_object($condition_query)) {
                            $s++;
                            ?>
                            <tr>
                                <td><?php echo $s . "."; ?></td>
                                <td colspan="3" valign="top"><?php echo $rec_condition->condition; ?> </td>
                                <?php
                                $sql_query = query($sql);
                                while ($rec_sup = fetch_object($sql_query)) {
                                    ?>
                                    <td colspan="3"><?php echo findValue("select value from price_comparison_condition_details where comparisonid=$comparison_id and supplier_id='$rec_sup->supplier_id' and conditionid='$rec_condition->conditionid'"); ?> </td>
                                <?php } ?>
                            </tr>
                            <?php
                        }
                        ?>
                        <?php
                    }
                }
                ?>
                <tr style="background-color:#E6E6F2">
                    <td colspan="3" valign="top"> Quotation Reference No:
                        <?php
                        if ($rec_com->status < 4) {
                            ?>
                            <a href='pop_reference_change.php?comparisonid=<?php echo $comparison_id; ?>&supplierids=<?php echo $supplier_ids; ?>&productid=<?php echo $rec_price->productid; ?>' target="_blank">Edit</a> 
                        <?php } ?>
                    </td>
                    <?php
                    $sql_query = query($sql);
                    while ($rec_sup = fetch_object($sql_query)) {
                        ?>
                        <td colspan="3"><?php echo findValue("select reference from comparative_referance where comparison_id='$comparison_id' and supplier_id 	='$rec_sup->supplier_id' "); ?> </td>
                    <?php } ?>
                </tr>
                <tr style="background-color:#D5D5AA">
                    <td colspan="3" valign="top"> <strong>Grand Total: </strong></td>
                    <?php
                    $sql_query = query($sql);
                    while ($rec_sup = fetch_object($sql_query)) {
                        ?>
                        <td colspan="3" align=right><strong>
                                <?php
                                $total_amount = 0;
                                $query_pri = query("select com.unite_price*com.quantity as value
                                    from price_comparison_details com
                                    where com.comparison_id='$comparison_id' and supplier_id='$rec_sup->supplier_id' 
                                        group by productid order by supplier_id asc");
                                while ($rec_price = fetch_object($query_pri)) {
                                    $total_amount += $rec_price->value;
                                }
                                echo number_format($total_amount, 2, '.', ',');
                                ?>
                            </strong> </td>
                        <?php
                    }

                    if ($group_item == 1) {
                        ?>
                    </tr>
                    <tr>
                        <td colspan="<?php echo $colspan2; ?>"><b> &nbsp;Spec.: <a href='specification_change.php?comparisonid=<?php echo $comparison_id; ?>' target="_blank">Edit</a> | <a href='pop_specification.php?supplierids=<?php echo $supplier_ids; ?>&compersionid=<?php echo $comparison_id; ?>' target="_blank">Add </a> </b></td>
                    </tr>
                    <?php
                    //echo "SELECT `condition`, conditionid FROM `price_coparison_specification` where comparisonid='$comparison_id'";
                    $spec_query2 = query("SELECT `condition`, conditionid FROM `price_coparison_specification` where comparisonid='$comparison_id'");
                    while ($rec_sep2 = fetch_object($spec_query2)) {
                        ?>
                        <tr>

                            <td colspan="3" valign="top"><?php echo $rec_sep2->condition; ?> </td>
                            <?php
                            $sql_query = query($sql);
                            while ($rec_sup = fetch_object($sql_query)) {
                                ?>
                                <td colspan="3"><?php echo findValue("select value from price_comparison_specefication_details where comparisonid='$comparison_id' and supplier_id='$rec_sup->supplier_id' and conditionid='$rec_sep->conditionid'"); ?> </td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="<?php echo $colspan2; ?>"><b><a href='condition_change.php?comparisonid=<?php echo $comparison_id; ?>' target="_blank">Edit</a> | <a href='pop_service.php?supplierids=<?php echo $supplier_ids; ?>>&compersionid=<?php echo $comparison_id; ?>' target="_blank">Add </a></b></td>
                    </tr>
                    <?php
                    $condition_query = query("select * from price_comparison_condition where comparisonid='$comparison_id' ");
                    while ($rec_condition = fetch_object($condition_query)) {
                        ?>
                        <tr>
                            <td colspan="3" valign="top"><?php echo $rec_condition->condition; ?> </td>
                            <?php
                            $sql_query = query($sql);
                            while ($rec_sup = fetch_object($sql_query)) {
                                ?>
                                <td colspan="3"><?php echo findValue("select value from price_comparison_condition_details where comparisonid='$comparison_id' and supplier_id='$rec_sup->supplier_id' and conditionid='$rec_condition->conditionid'"); ?> </td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                }
                ?> 
            </table>
            <?php
            $proposed_by = $rec_com->proposed_name;
            $proposed_designation = $rec_com->proposed_designation;
            $reviewed_by = $rec_com->reviewed_name;
            $reviewed_designation = $rec_com->reviewed_designation;
            $chairman = $rec_com->chairman_name;
            $chairman_designation = $rec_com->chairman_designation;
            $member1 = $rec_com->member1_name;
            $member1_designation = $rec_com->member1_designation;
            $member2 = $rec_com->member2_name;
            $member2_designation = $rec_com->member2_designation;
            $member_secretary = $rec_com->member_secretary_name;
            $secretary_designation = $rec_com->secretary_designation;
            $procurement_member = $rec_com->procurement_member_name;
            $procur_member_desig = $rec_com->procurement_member_designation;
            $recommendation = $rec_com->recommendation;
            ?>
            <a href="evaluation_committee.php?compersionid=<?php echo $comparison_id; ?>" target="_blank"class="button"<span class = "icon plus"></span>Add/ Edit Committee Info</a>

            <table class="ui-state-default">
                <tr>
                    <td  align="left" colspan="4"><p><strong><u>Recommendation:</u></strong></p>
                        <p><?php echo $recommendation; ?></p>
                    </td>
                </tr>
                <tr style="background-color:#E4E4E4">
                    <td>&nbsp;</td>
                    <td><b>Name</b></td>
                    <td><b>Designation</b></td>
                    <td><b>Signature</b></td>
                </tr>
                <tr>
                    <td>Proposed By </td>
                    <td><?php echo $proposed_by; ?></td>
                    <td><?php echo $proposed_designation; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Review By </td>
                    <td><?php echo $reviewed_by; ?></td>
                    <td><?php echo $reviewed_designation; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Quotation Opening Committee </strong> </td>
                </tr>
                <tr>
                    <td>Chairman</td>
                    <td><?php echo $chairman; ?></td>
                    <td><?php echo $chairman_designation; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Member</td>
                    <td><?php echo $member1; ?></td>
                    <td><?php echo $member1_designation; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Member</td>
                    <td><?php echo $member2; ?></td>
                    <td><?php echo $member2_designation; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Member Secretary </td>
                    <td><?php echo $member_secretary; ?></td>
                    <td><?php echo $secretary_designation; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Reviewed By </td>
                    <td><?php echo $procurement_member; ?></td>
                    <td><?php echo $procur_member_desig; ?></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <br/>

            <input type="hidden" name="parent_url" id="parent_url" value="<?php echo $_SERVER['REQUEST_URI'] ?>"/>
            <?php
            if ($rec_com->status == 1) {
                ?>
                <input type="submit" value='Send For Approval Note' name='save' id="approved" />
            <?php } elseif ($rec_com->status == 2) { ?>
                <a href='../product_approval/approval_note.php?comparison_id=<?php echo $comparison_id; ?>' name="approval_note" class="button" value="approval Note">Create Approval Note</a>
            <?php } ?>
        </form>
    </div>
</div>


<?php include("../body/footer.php"); ?>