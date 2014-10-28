<?php
$HistorySQL = "SELECT CONCAT(e.FIRST_NAME, ' ', e.LAST_NAME, '(',CARD_NO,')') AS app_person,
SL, wm.CREATED_DATE, wm.APPROVAL_COMMENT

FROM workflow_manager wm
LEFT JOIN requisition r ON r.REQUISITION_ID=wm.REQUISITION_ID
LEFT JOIN employee e ON e.EMPLOYEE_ID = wm.EMPLOYEE_ID
WHERE wm.REQUISITION_ID='$search_id' AND APPROVE_STATUS='1'";
$QueryResult = query($HistorySQL);
//AND APPROVE_STATUS=1
?>

<h3>Requisition Approval History</h3>
<table class="ui-state-default">
    <thead>
    <th width="20">SL</th>
    <th width="300">Name</th>
    <th width="100">Date</th>
    <th>Approval Comment</th>
</thead>
<tbody>
    <?php
    while ($rowQuery = fetch_object($QueryResult)) {
        ?>
        <tr>
            <td><?php echo++$no; ?>.</td>
            <td><?php echo $rowQuery->app_person; ?></td>
            <td><?php echo bddate($rowQuery->CREATED_DATE); ?></td>
            <td><?php echo $rowQuery->APPROVAL_COMMENT; ?></td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>

