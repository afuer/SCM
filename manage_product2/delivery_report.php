<?php
include '../lib/DbManager.php';

$action = "and dh.challan_id IS NOT NULL and ch.status =0";
$office_type = getParam('office_type');


$btnchallan = getParam('btnchallan');
$done = getParam('done');
$deliverinfo = getParam('deliverinfo');

if (!empty($btnchallan)) {
    foreach ($done as $key => $value) {

        $sqlUp = "update challan set status=1, delivery_info='$deliverinfo[$value]' where challanid='$value'";

        $db->sql($sqlUp);
    }
}



include("../body/header.php");
?>


<div class="easyui-layout" style="width:950px; height:700px; margin: auto;">  
    <div data-options="region:'center',iconCls:'icon-ok'">  
        <div id="tt" class="easyui-tabs" data-options="fit:true,border:false,plain:true">  

            <div title="Delevery Challan Create Divisiton" >  


                <form method="POST" action="">
                    <table width="100%" class="ui-state-default">

                        <thead>
                        <th width='20'><?php etr("Sl.") ?></th>
                        <th width='100'><?php etr("Challan. No") ?></th>
                        <th>Branch/Dept.Name</th>
                        <th width='100' align="center">Amount</th>
                        <th>Courier Service </th>
                        <th width='20'>&nbsp;</th>
                        </thead>
                        <?php
                        $sql_delever = "select ch.challanid, ch.challan_no, b.BRANCH_DEPT_NAME, ot.OFFICE_NAME,
                        sum(chi.uniteprice*chi.quantity) as total, apdh.req_id, so.REQUISITION_ID

                        from challan ch 
                        left join challan_item chi on chi.challanid = ch.challanid 
                        LEFT JOIN app_product_delivery_history AS apdh ON apdh.challan_id=ch.challanid
                        LEFT JOIN requisition AS so ON so.REQUISITION_ID=apdh.req_id
                        LEFT JOIN branch_dept b ON b.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
                        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=b.OFFICE_TYPE_ID
                        WHERE so.OFFICE_TYPE_ID=1 and ch.status =0 AND so.PROCESS_DEPT_ID='$ProcessDeptId'
                        GROUP BY chi.challanid";

                        $sql = $db->query($sql_delever);
                        $sub_total = "";
                        while ($rec = fetch_object($sql)) {
                            $sl++;
                            ?> 
                            <tr>
                                <td><?php echo $sl; ?></td>
                                <td><strong><a href='challan_details.php?challanid=<?php echo $rec->challanid; ?>&requisition=<?php echo $rec->REQUISITION_ID; ?>' target="_blank"><?php echo $rec->challan_no; ?></a></strong></td>
                                <td><?php $rec->BRANCH_NAME; ?></td>
                                <td align="center"><?php echo formatMoney($rec->total); ?></td>
                                <td align="center"><textarea name="deliverinfo[<?php echo $rec->challanid; ?>]" cols="28"></textarea></td>
                                <td align="center"><input type="checkbox" name="done[<?php echo $rec->req_id; ?>]" value="<?php echo $rec->challanid; ?>"></td>
                            <input type="hidden" name="order_id[<?php echo $rec->req_id; ?>]" value="<?php echo $rec->req_id; ?>" />
                            </tr>
                            <?php
                            $sub_total = $sub_total + $rec->total;
                        }
                        ?>

                        <tr>
                            <td colspan="3" align="right"><strong>Total</strong></td>
                            <td align="center"><?php echo formatMoney($sub_total); ?></td>
                            <td align="center">&nbsp;</td>
                            <td align="center"><input type="submit" name="btnchallan" value="Deliverd"></td>
                        </tr>
                    </table>
                </form>
            </div>  

            <div title="Delevery Challan Create Branch">
                <form action="" method="POST"> 
                    <table width="100%" class="ui-state-default">
                        <thead>
                        <th width='20'><?php etr("Sl.") ?></th>
                        <th width='100'><?php etr("Challan. No") ?></th>
                        <th>Branch/Dept.Name</th>
                        <th width='100' align="center">Amount</th>
                        <th>Courier Service </th>
                        <th width='20'>&nbsp;</th>
                        </thead>
                        <?php
                        $sql_delever = "select ch.challanid, ch.challan_no, bd.BRANCH_DEPT_NAME, ot.OFFICE_NAME,
                        ch.requisition_id

                        from challan ch 
                        left join challan_item chi on chi.challanid = ch.challanid 
                        LEFT JOIN requisition r ON r.REQUISITION_ID IN (ch.requisition_id)
						LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=r.BRANCH_DEPT_ID
						LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=bd.OFFICE_TYPE_ID
                        WHERE ot.OFFICE_TYPE_ID=2 AND ch.status =0 AND r.PROCESS_DEPT_ID='$ProcessDeptId'
                        GROUP BY chi.challanid ORDER BY chi.challanid DESC";

                        $sql = $db->query($sql_delever);
                        $sub_total = "";
                        while ($rec = fetch_object($sql)) {
                            $sl++;

                            $total = findValue("SELECT sum(IFNULL(ci.uniteprice,0)*IFNULL(ci.quantity,0)) 
                            FROM challan_item ci 
                            WHERE ci.challanid='$rec->challanid'
                            GROUP BY ci.challanid");
                            ?> 
                            <tr>
                                <td class="sn"><?php echo $sl; ?></td>
                                <td><strong><a href='challan_details.php?challanid=<?php echo $rec->challanid; ?>&requisition=<?php echo $rec->requisition_id; ?>' target="_blank"><?php echo $rec->challan_no; ?></a></strong></td>
                                <td><?php echo $rec->BRANCH_DEPT_NAME; ?></td>
                                <td align="center"><?php echo formatMoney($total); ?></td>
                                <td align="center"><textarea name="deliverinfo[<?php echo $rec->challanid; ?>]" cols="28"></textarea></td>
                                <td align="center"><input type="checkbox" name="done[<?php echo $rec->req_id; ?>]" value="<?php echo $rec->challanid; ?>"></td>
                            <input type="hidden" name="order_id[<?php echo $rec->req_id; ?>]" value="<?php echo $rec->req_id; ?>" />
                            </tr>
                            <?php
                            $sub_total = $sub_total + $rec->total;
                        }
                        ?>

                        <tr>
                            <td colspan="3" align="right"><strong>Total</strong></td>
                            <td align="center"><?php echo formatMoney($sub_total); ?></td>
                            <td align="center">&nbsp;</td>
                            <td align="center"><input type="submit" name="btnchallan" value="Deliverd"></td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>  
    </div>
</div>






<?php include("../body/footer.php"); ?>