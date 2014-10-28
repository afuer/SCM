<?php
include_once '../lib/DbManager.php';

$object_name = 'delegation_authority';
$object_id = strtoupper($object_name) . '_ID';
include '../body/header.php';

$sql = "SELECT APPROVAL_NOTE_ID, CS_ID, REF, ra.DATE, CC, `SUBJECT`, BODY, FOOTER, 
    comparative_code,  rs.status_name,
    SUM(IFNULL(pcq.cs_qty,0)*IFNULL(pcd.unite_price,0)) AS 'total',
    (CASE WHEN ra.PRESENT_LOCATION_ID IS NOT NULL THEN (SELECT CONCAT(es.FIRST_NAME,' ',es.LAST_NAME)  FROM employee es WHERE es.EMPLOYEE_ID=ra.PRESENT_LOCATION_ID) ELSE (SELECT CONCAT(es.FIRST_NAME,' ',es.LAST_NAME)  FROM employee es WHERE es.EMPLOYEE_ID=ra.CREATED_BY) END) AS 'location'
    FROM requisition_approval ra
    INNER JOIN price_comparison pc ON pc.comparisonid=ra.CS_ID
    INNER JOIN price_comparison_details pcd ON pcd.comparison_id=pc.comparisonid
    INNER JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pc.comparisonid AND pcq.product_id=pcd.productid
    LEFT JOIN requisition_status rs ON rs.requisition_status_id=ra.`STATUS`
    WHERE ra.CREATED_BY='$employeeId' OR ra.PRESENT_LOCATION_ID='$employeeId'
    GROUP BY ra.APPROVAL_NOTE_ID ORDER BY CS_ID DESC";
$result_sql = query($sql);

//include '../lib/master_page.php';
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
                    <th field='6'>Total Amount</th>
                    <th field="8">Present Location</th>
                    <th field='5'>Status</th>
                    <th field='7'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysql_fetch_object($result_sql)) { ?>

                    <tr>
                        <td><?php echo++$sl; ?></td>
                        <td><?php echo $row->DATE; ?></td>
                        <td><?php echo $row->CS_ID; ?></td>
                        <td><?php echo $row->REF; ?></td>
                        <td><?php echo $row->total; ?></td>
                        <td><?php echo $row->location; ?></td>
                        <td><?php echo $row->status_name; ?></td>
                        <td><a href='approval_note_approve.php?mode=approve&comparison_id=<?php echo $row->CS_ID ?>'>View</a></td>
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