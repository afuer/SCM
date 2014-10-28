<?php
include '../lib/DbManager.php';


//include('salesorder.inc.php');

$starttime = getParam('startdate');
//$starttime = $starttime == '' ? firstDayMonth() : $starttime;
$endtime = getParam('enddate');
//$endtime = $endtime == '' ? lasDayMonth() : $endtime;
$res = '';
if ($starttime != NULL && $endtime != NULL) {
    $rec = " and ch.receive_date between '$starttime' and '$endtime'";
}

include("../body/header.php");


$btn_received = getParam('btn_received');
$done = getParam('done');

if (!empty($btn_received) && (!empty($done))) {
    foreach ($done as $key => $value) {
        $sqlUp = "update challan set status=2, received_by='$userName', receive_date=NOW() where challanid='$value'";

        if (sql($sqlUp)) {
            $chalan_item = query("select productid, quantity, challanid from challan_item where challanid='$value'");
            while ($rec_cha = fetch_object($chalan_item)) {

                $sal_item = "update requisition_details set status_app_level=4
            where status_app_level=3 and PRODUCT_ID='$rec_cha->productid'";

                sql($sal_item);
            }
            total_received_sr($requisitionId);
            partly_received_requisition($requisitionId);
        }
    }
}

$challan_list = rs2array(query("SELECT challan_status_id, challan_status_name FROM challan_status GROUP BY challan_status_name"));

$search = getParam('search');
if (isset($search)) {
    $goods_status = getParam('goods_status');

    if ($goods_status == 3) {
        $goods_status = '1,2';
    }
    $rec .= " and ch.status in($goods_status) ";
} else {
    $rec .= " and ch.status =1 ";
}
?>

<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Goods Recive List' style="padding: 10px 10px; background-color:white; "> 


        <form action="" method='GET'>
            <table width="100%" class="table">
                <tr>
                    <td width="100">Status:</td>
                    <td><?php comboBox('goods_status', $challan_list, $goods_status, FALSE); ?></td>
                    <td>From:</td>
                    <td><input type="text" name="startdate" class="data" value="<?php echo $starttime; ?>"/> To: <input type="text" name="enddate" class="data" value="<?php echo $endtime; ?>"/></td>
                    <td></td>
                </tr>
            </table>
            <input type="submit" name="search" value="Search" class="button"/>
        </form>
        <hr/>
        <form action="" method='POST'>
            <table class="ui-state-default">
                <thead>
                <th width='30'>SL.</th>
                <th width='100'>Challan No</th>
                <th width='120' align="center">Challan Date</th>
                <th width='100' align="center">Challan Qty</th>
                <th>Requisition No</th>
                <th align="right">Amount</th>
                <th width="20">Action</th>
                </thead>
                <?php
                $sql = "SELECT ch.challanid, status, 
                     r.REQUISITION_NO, ch.requisition_id,
                    ch.challan_no, DATE_FORMAT(ch.date_time,'%e-%b %Y') as date_time,
                    cs.challan_status_name, SUM(quantity) AS 'Qty', r.REQUISITION_ID,
                    SUM(IFNULL(chi.quantity,0)*IFNULL(chi.uniteprice,0)) AS total
                    from challan ch 
                    left join challan_item chi on chi.challanid = ch.challanid
                    LEFT JOIN requisition r ON r.REQUISITION_ID IN(ch.requisition_id) 
                    LEFT JOIN challan_status AS cs ON cs.challan_status_id=ch.`status`
                    where r.CREATED_BY='$employeeId' $rec
                    group by chi.challanid having ch.challan_no > 0";
                $query = query($sql);


                while ($rec = fetch_object($query)) {
                    ?> 
                    <tr>
                        <td><?php echo++$sl; ?></td>
                        <td><strong><a href='challan_details.php?challanid=<?php echo $rec->challanid; ?>&requisition=<?php echo $rec->requisition_id; ?>' target="_blank" ><?php echo $rec->challan_no; ?></a></strong></td>
                        <td align="center"><?php echo $rec->date_time; ?></td>
                        <td align="center"><?php echo $rec->Qty; ?></td>
                        <td>
                            <?php
                            $sql_req = "SELECT req_id, r.REQUISITION_NO 
                                FROM app_product_delivery_history dh 
                                INNER JOIN requisition r ON r.REQUISITION_ID=dh.req_id 
                                WHERE challan_id='$rec->challanid' GROUP BY REQUISITION_ID";

                            $result_req = query($sql_req);

                            while ($row_Req = fetch_object($result_req)) {
                                ?>
                                <a href="reco_details.php?reco_id=<?php echo $row_Req->req_id; ?>" target="_blank"><?php echo $rec->REQUISITION_NO; ?></a><br>
                                <?php
                            }
                            ?>
                        </td>
                        <td align="right"><?php echo $rec->total; ?></td>
                        <td align="center">
                            <?php
                            if ($rec->status == 1) {
                                ?>
                                <input type="checkbox" name="done[<?php echo $rec->challanid; ?>]" value="<?php echo $rec->challanid; ?>"/>
                            <?php } ?>   
                        </td>
                    </tr>
                    <?php
                    $sub_total = $sub_total + $rec->total;
                }
                ?>
                <tr>
                    <td colspan="3" align="right">&nbsp;</td>
                    <td align="center"></td>
                    <td align="right"><strong>Total:</strong></td>
                    <td align="center"><?php echo formatMoney($sub_total); ?></td>
                    <td align="center"></td>
                </tr>
            </table>
            <input type="submit" name="btn_received" value="Received" class="button"/>
            <br/>


        </form>
    </div>
</div>
<?php include("../body/footer.php"); ?>