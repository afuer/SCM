<?php
include_once '../lib/DbManager.php';

$requisitionId = getParam('requisitionId');
include '../body/header.php';

$sql = "SELECT APPROVAL_NOTE_ID, CS_ID, REF, ra.DATE, CC, `SUBJECT`, BODY, FOOTER, comparative_code,  ra.`STATUS`,
SUM(IFNULL(pcq.cs_qty,0)*IFNULL(pcd.unite_price,0)) AS 'total', rs.STATUS_NAME, ul.USER_LEVEL_NAME, pc.comparative_code

FROM requisition_approval ra
INNER JOIN price_comparison pc ON pc.comparisonid=ra.CS_ID
INNER JOIN price_comparison_details pcd ON pcd.comparison_id=pc.comparisonid
INNER JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pc.comparisonid AND pcq.product_id=pcd.productid
INNER JOIN requisition_status rs ON rs.REQUISITION_STATUS_ID=ra.`STATUS`
LEFT JOIN user_level ul ON ul.USER_LEVEL_ID=ra.USER_LEVEL_ID

WHERE ra.USER_LEVEL_ID='$UserLevelId' OR ra.PRESENT_LOCATION_ID='$employeeId'
GROUP BY ra.APPROVAL_NOTE_ID ORDER BY APPROVAL_NOTE_ID DESC";
$result_sql = query($sql);

?>
<br/><br/>
<input type="hidden" name="object_name" id="object_name" value="<?php echo $object_name; ?>"/>
<input type="hidden" name="object_id" id="object_id" value="<?php echo $object_id; ?>"/>
<script type="text/javascript" src="include.js"></script>

<div class="easyui-layout" style="width:1100px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Approval Note' style="padding: 10px 10px; background-color:white; "> 

        <table class="easyui-datagrid">
            <thead>
                <tr>
                    <th field='1'>SL.</th>
                    <th field='2'>Date</th>
                    <th field='3'>CS Code</th>
                    <th field='4'>Rfe</th>
                    <th field='5'>Status</th>
                    <th field='8'>Present Location</th>
                    <th field='7'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysql_fetch_object($result_sql)) { ?>

                    <tr>
                        <td><?php echo++$sl; ?></td>
                        <td><?php echo $row->DATE; ?></td>
                        <td><?php echo $row->comparative_code; ?></td>
                        <td><?php echo $row->REF; ?></td>
                        <td><?php echo $row->STATUS_NAME; ?></td>
                        <td><?php echo $row->USER_LEVEL_NAME; ?></td>
                        <td><a href='approval_note_approve.php?comparison_id=<?php echo $row->CS_ID ?>'>View</a></td>
                    </tr>
                    <?php
                }
                ?>

            </tbody>

        </table>

    </div>
</div>





<?php
include '../body/footer.php';
?>