<?php
include '../lib/DbManager.php';
include("../body/header.php");

$from_date = getParam('startdate');
$end_date = getParam('enddate');

$starttime = getParam('startdate');
$endtime = getParam('enddate');

$status = getParam("status");
$search = getParam('search');
$searchkey = getParam('searchkey');

$res = '';
$res .= ($from_date == '' || $end_date == '') ? "" : " and date between '$from_date' and '$end_date'";
$res .= $UserLevelId == 5 ? " and prm.createby = '$userName'" : " AND (prm.USER_LEVEL_ID = '$UserLevelId' OR prm.PRESENT_LOCATION_ID = '$user_name')";


if (isset($search)) {
    $condition = '';
    $condition .= $status == '' ? '' : " and prm.status='$status'";
    $condition .=$searchkey == '' ? '' : " and prm.comparative_code like '%$searchkey%' or prm.comparisonid like '%$searchkey%' or prm.createby like '%$searchkey%' or e.FIRST_NAME like '%$searchkey%'";
} else if ($employ_level != "") {
    $condition = " and prm.status='$employ_level'";
}

$sql = "SELECT prm.comparisonid,
                    prm.comparative_code,
                    prm.approved,
                    e.FIRST_NAME, e.LAST_NAME,
                    e.CARD_NO, 
                    prm.date, 
                    (CASE WHEN prm.PRESENT_LOCATION_ID IS NULL THEN sta.USER_LEVEL_NAME ELSE CONCAT(e.FIRST_NAME,' ',e.LAST_NAME) END) AS 'location',
                    prm.status, CS_ID
                    from price_comparison prm
                    left join price_comparison_details pr_d on prm.comparisonid = pr_d.comparison_id
                    left join product pr on pr.PRODUCT_ID= pr_d.productid
                    left join master_user u on prm.createby = u.USER_NAME
                    left join employee e on e.EMPLOYEE_ID = u.EMPLOYEE_ID
                    left join user_level sta on prm.USER_LEVEL_ID = sta.USER_LEVEL_ID  
                    LEFT JOIN requisition_approval ra ON ra.CS_ID=prm.comparisonid
                    WHERE prm.poid IS NULL  $res $condition 
                    GROUP BY prm.comparisonid ORDER BY prm.comparisonid desc";

$query_com = query($sql);
?>

<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="js/ckeditor/config.js"></script>

<div class="easyui-layout" style="width:1100px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Comparative Statement List' style="padding: 10px 10px; background-color:white; "> 

        <form action="" method='GET'>
            <table width="100%" class="ui-state-default">
                <tr>
                    <td>Search Key</td>
                    <td><input type="text" name="searchkey" value="<?php echo $searchkey; ?>" /></td>
                    <td>Status</td>
                    <td><?php comboBox('status', $cs_status_list, $status, TRUE) ?></td>

                </tr>
                <tr>
                    <td>Date From</td>
                    <td><input type="text" name="startdate" value="<?php echo $starttime; ?>" class="easyui-datebox" /></td>
                    <td>to</td>
                    <td><input type="text" name="enddate" value="<?php echo $endtime; ?>"  class="easyui-datebox"/></td>
                </tr>
            </table>
            <input type="submit" name="search" value="Search" />
        </form>

        <table class="ui-state-default" width="100%" >
            <thead>
            <th width='20'>SL.</th>
            <th width='100'>Date</th>
            <th width='120'>Comparison No</th>
            <th width="80">Approval Note</th>
            <th>Requisition For</th>
            <th width='150'>Present Location</th>
            <th width='100'>Status</th>
            </thead>
            <?php
            while ($rec_com = fetch_object($query_com)) {
                $sl++;
                //echo $rec_com->status . '<br>';
                $link = $rec_com->status == 1 ? "evaluation_statement.php?comparison_id=$rec_com->comparisonid" : "../product_approval/approval_note.php?comparison_id=$rec_com->comparisonid";
                ?>
                <tr>
                    <td><?php echo $sl . "."; ?></td>
                    <td><?php echo bddate($rec_com->date); ?></td>
                    <td><a href="evaluation_statement.php?comparison_id=<?php echo $rec_com->comparisonid; ?>" target="_blank"><?php echo po_no($rec_com->comparisonid); ?></a></td>
                    <td><?php echo $rec_com->CS_ID; ?></td>
                    <td><?php echo $rec_com->FIRST_NAME . ' ' . $rec_com->LAST_NAME . ' (' . $rec_com->CARD_NO . ')'; ?></td>
                    <td><?php echo $rec_com->location; ?></td>
                    <td><?php echo cs_status($rec_com->status); ?></td>
                </tr>  
            <?php } ?>
        </table>
        <br/>
        <input type="hidden" name="parent_url" id="parent_url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
    </div>
</div>
<?php include("../body/footer.php"); ?>