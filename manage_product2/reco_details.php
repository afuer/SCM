<?php
include '../lib/DbManager.php';




$reco_id = getParam('reco_id');
$productid = getParam("productid");

$status_id = array("", "0", "1", "2", "3", "4", "5", "6");
$status_name = array("", "New", "Reviewed", "Processing", "Waiting for received", "Partial Received", "Goods Received", "Approved");
$approved_hod = getParam("approved_hod");
$approved_amd = getParam("approved_amd");
if (isset($approved_hod)) {
    $db->sql("insert into approval_recoquisition (historyid, approval_type, user_id, designation, comments) 
	values ($reco_id, 1, $employeeid, '$designationid','Approved')");

    //sql("update salesorder set hod_procure='$user' where orderid=$reco_id");
}

include '../body/header.php';
?>

<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Delivery Products' style="padding: 10px 10px; background-color:white; "> 

        <form>
            <table width="100%" border="0" cellspacing="1" cellpadding="2" id="hor-minimalist-b" bgcolor="#FFFFFF">
                <tr>
                    <td width="70%" valign="top"><img src="../public/images/logo.gif" height="60"/></td>
                    <td width="30%" valign="top"><b>Prime Bank Center</b><br /> 136, Gulshan Avenue, Gulshan-2, <br />Dhaka-1212, Bangladesh<br />Web: www.primebank.com</td>
                </tr>
            </table>
            <fieldset>
                <legend style=" padding:2px; font-weight:bold; text-indent:20px;">Requisition Details</legend>


                <?php
                $rec = $db->find("select s.REQUISITION_NO,
                                e.CARD_NO, e.EMPLOYEE_ID,
                                REQUISITION_STATUS_ID, 
                                s.CREATED_BY,
                                REQUISITION_DATE, 
                                justification, 
                                specification,
                                costcenter_id, 
                                DIVISION_NAME, 
                                s.OFFICE_TYPE_ID, 
                                s.branch_dept_id, 
                                expected_date, 
                                p.PRIORITY_ID,
                                bd.BRANCH_DEPT_NAME,
                                REMARK, REFERENCE

                                from requisition s
                                left join division dv on dv.DIVISION_ID=s.DIVISION_ID
                                left join priority p on p.PRIORITY_ID=s.PRIORITY_ID
                                LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=s.BRANCH_DEPT_ID
                                LEFT JOIN employee e ON e.EMPLOYEE_ID=s.CREATED_BY
                                where REQUISITION_ID='$reco_id'");
                ?>

                <fieldset>
                    <table class="table">
                        <tr>
                            <td width="120"><strong>Requisition No:</strong></td>
                            <td><?php echo $rec->REQUISITION_NO; ?></td>
                            <td width="140"><strong>Submission Date: </strong></td>
                            <td width="150"><?php echo bddate($rec->REQUISITION_DATE); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Requisition By :</strong></td>
                            <td><?php echo user_identity($rec->CARD_NO); ?></td>
                            <td><strong>Required Date:</strong></td>
                            <td><?php echo bddate($rec->REQUISITION_DATE); ?></td>
                        </tr>
                        <tr class='even'>
                            <td valign="top"><strong>Justification : </strong></td>
                            <td colspan="3" valign="top"><?php echo $rec->justification; ?> </td>
                        </tr>
                        <tr>
                            <td valign="top"><strong>Specification : </strong></td>
                            <td colspan="3" valign="top"><?php echo $rec->specification; ?></td>
                        </tr>

                        <tr class='even'>
                            <td><strong>Branch/Dept :</strong></td>
                            <td><?php echo $rec->BRANCH_DEPT_NAME; ?></td>
                            <td><strong>Status</strong> <strong>:</strong></td>
                            <td><?php echo str_replace($status_id, $status_name, $rec->REQUISITION_STATUS_ID); ?></td>
                        </tr>

                        <tr class='even'>
                            <td colspan="2"><?php echo $rec->REFERENCE; ?></td>
                            <td><strong>Address :</strong></td>
                            <td><?php echo $rec->REMARK; ?></td>

                        </tr>
                    </table>
                </fieldset>


                <table class="ui-state-default">
                    <thead>
                        <tr>
                            <th width="30">SL.</th>
                            <th width="77">Product No </th>
                            <th>Description of Materials/ Service Required</th>
                            <th width="69" >Quantity</th>
                            <th width="75" >Unit</th>
                            <th width="75" >Unit Price</th>
                            <?php
                            if ($employ_level > 1) {
                                ?>
                                <th colspan="2">Total Cost<br/>(Aprox) </th>
                                <th width="102" align="center">Last PO Date </th>
                                <th width="101" align="center">Last PO Qty </th>
                                <th width="101" align="center">Last PO Price </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <?php
                    $sql = $db->query("select s.PRODUCT_ID, 
                        so.branch_dept_id, so.OFFICE_TYPE_ID, 
                        pr.PRODUCT_CODE, s.QTY, s.UNIT_PRICE,
                        pr.PRODUCT_NAME, ut.UNIT_TYPE_NAME
                        from requisition_details s 
                        left join requisition so on s.REQUISITION_ID=so.REQUISITION_ID
                        left join product pr on pr.PRODUCT_ID=s.PRODUCT_ID
                        LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=pr.UNIT_TYPE_ID
                        
                        where s.REQUISITION_ID='$reco_id'");

                    $sub_total_cost = 0;
                    while ($rec2 = fetch_object($sql)) {
                        $sl++;
                        if ($productid == $rec2->productid) {
                            $selected = "style='background-color:#EBFFD7'";
                        } else {
                            $selected = "";
                        }

                        $rec_delivery = $db->find("select 
                                        poi.qty, 
                                        poi.unit_price,
                                        po.order_date 	 	 
                                        from purchase_order po
                                        inner join purchase_order_details poi on po.purchase_order_id=poi.purchase_order_id
                                        where poi.product_id='$rec2->productid' and 
                                        poi.branch_dept_id='$ProcessDeptId' order by poi.purchase_order_id desc");
                        ?>
                        <tr class='even' <?php echo $selected; ?>>
                            <td><?php echo $sl; ?></td>
                            <td align="center" ><?php echo $rec2->PRODUCT_CODE; ?></td> 
                            <td><?php echo $rec2->PRODUCT_NAME; ?></td>
                            <td align="center">
                                <?php echo $rec2->QTY; ?></td>
                            <td align='center'><?php echo $rec2->UNIT_TYPE_NAME; ?></td>
                            <td align='center'><?php echo $rec2->UNIT_PRICE; ?></td>
                            <?php
                            if ($employ_level > 1) {
                                ?> 
                                <td align=center><?php echo formatMoney($rec2->supplier_unitprice); ?></td>
                                <td colspan="2" align='center'><?php
                                    echo formatMoney($rec2->supplier_unitprice * $rec2->quantity);
                                    $sub_total_cost+=$rec2->supplier_unitprice * $rec2->quantity;
                                    ?>
                                </td>


                                <td align='center'><?php echo bddate($rec_delivery->orderdate); ?></td>
                                <td align='center'><?php echo $rec_delivery->quantity; ?></td>
                                <td align='center'><?php echo formatMoney($rec_delivery->unitprice); ?></td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr class='even' <?php echo $selected; ?>>
                        <td width="48" align="center" >&nbsp;</td>
                        <td width="77" align="center" >&nbsp;</td>
                        <td align='center' colspan="2"><strong>Grand Total</strong></td>
                        <td align="center" ><strong><?php echo formatMoney($sub_total_cost); ?></strong></td>
                        <td></td>
                    </tr>
                </table>
                <br><hr><br>
                <table>
                    <tr>
                        <?php
                        echo "<td width='120' align='center' valign='top'>";
                        echo user_identity_with_designation($rec->EMPLOYEE_ID);
                        echo bddate($rec->REQUISITION_DATE);
                        echo " ___________________<br />";
                        echo "<b>Prepared By:</b>";
                        echo "</td>";

                        $requisition_process_history = $db->query("SELECT
                        wm.EMPLOYEE_ID,
                        employee.EMPLOYEE_ID,
                        DESIGNATION_NAME,
                        wm.CREATED_DATE,
                        wm.WORKFLOW_MANAGER_ID,
                        CONCAT(FIRST_NAME,' ',LAST_NAME)As FIRST_NAME ,
                        designation.DESIGNATION_NAME
                        
                        FROM workflow_manager wm
                        INNER JOIN employee ON wm.EMPLOYEE_ID = employee.EMPLOYEE_ID
                        left JOIN designation ON employee.DESIGNATION_ID = designation.DESIGNATION_ID
                        where requisition_id='$reco_id'");
                        $count = 1;
                        while ($requisition_process_history_sl = fetch_object($requisition_process_history)) {

                            if ($count >= 6) {
                                echo "</tr>";
                                echo "<tr>";
                            }
                            echo "<td width='120' align='center' valign='top'>";
                            echo user_identity_with_designation($requisition_process_history_sl->EMPLOYEE_ID);
                            echo bddate($requisition_process_history_sl->CREATED_DATE);
                            echo " ___________________<br />";
                            echo "<b>Reviewed By:</b>";
                            echo "</td>";

                            if ($count > 5) {
                                $count = 1;
                            }
                            $count++;
                        }
                        ?>
                    </tr>  
                </table>  
            </fieldset>	
            <input type="hidden" name="reco_id" value="<?php echo $reco_id; ?>" />
        </form>
    </div>
</div>

<?php include '../body/footer.php'; ?>

