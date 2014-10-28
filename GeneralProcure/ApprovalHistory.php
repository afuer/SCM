<?php
$HistorySQL = "SELECT CONCAT(ed. FIRST_NAME, '  ', ed.LAST_NAME, '->', ed.CARD_NO,' (',d.DESIGNATION_NAME,')')AS app_person,
	SL, wm.CREATED_DATE, wm.APPROVAL_COMMENT

	FROM workflow_manager wm
	LEFT OUTER JOIN employee ed ON ed.EMPLOYEE_ID = wm.EMPLOYEE_ID
	LEFT JOIN designation d ON d.DESIGNATION_ID=wm.DESIGNATION_ID
	WHERE wm.REQUISITION_ID='$SearchId'
	ORDER BY wm.WORKFLOW_MANAGER_ID DESC";
$QueryResult = query($HistorySQL);

//  AND APPROVE_STATUS=1
?>

<div class="panel-header" style="width: 788px;">Requisition Approval History</div>
<table class="ui-state-default">
    <thead>
        <tr>
            <th field="1" width="30">S/N</th>
            <th field="2" width="250">Name</th>
            <th field="3" width="100">Date</th>
            <th field="4">Approval Comment</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($rowQuery = fetch_object($QueryResult)) {
            ?>
            <tr>
                <td><?php echo++$no; ?></td>
                <td><?php echo $rowQuery->app_person; ?></td>
                <td><?php echo bddate($rowQuery->CREATED_DATE); ?></td>
                <td><?php echo $rowQuery->APPROVAL_COMMENT; ?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>


