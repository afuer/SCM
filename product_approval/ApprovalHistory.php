<?php
$HistorySQL = "SELECT CONCAT(e.FIRST_NAME, ' ', e.LAST_NAME,  '->', e.CARD_NO,'(',d.DESIGNATION_NAME,')') AS app_person,
SL, wm.CREATED_DATE, wm.APPROVAL_COMMENT

FROM workflow_manager wm
LEFT JOIN employee e ON e.EMPLOYEE_ID = wm.CREATED_BY
LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
WHERE wm.REQUISITION_ID='$searchId' AND APPROVE_STATUS='1' AND wm.MODULE_NAME='Product Approval'";
$QueryResult = $db->query($HistorySQL);
//AND APPROVE_STATUS=1
?>

<h3></h3>
<table  class="ui-state-default" title="Requisition Approval History">
    <thead>
        <tr>
            <th field='1' width="20"><b>SL.</b></th>
            <th field='2' width="100"><b>Date</b></th>
            <th field='3'>Employee Name</th>
            <th field='5'><b>Comments</b></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $num_rows = mysql_num_rows($QueryResult);
        if ($num_rows > 0) {
            while ($rowQuery = fetch_object($QueryResult)) {
                ?>
                <tr>
                    <td><?php echo++$no; ?>.</td>
                    <td><?php echo bddate($rowQuery->CREATED_DATE); ?></td>
                    <td><?php echo $rowQuery->app_person; ?></td>
                    <td><?php echo $rowQuery->APPROVAL_COMMENT; ?></td>
                </tr> 
                <?php
            }
        } else {
            echo "<tr><td colspan='4'>No Record Foud</td></tr>";
        }
        ?>
    </tbody>
</table>


