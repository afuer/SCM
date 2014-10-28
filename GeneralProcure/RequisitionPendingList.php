<?php
include_once '../lib/DbManager.php';
include("../body/header.php");

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = (int) (!isset($_GET["limit"]) ? 20 : $_GET["limit"]);
$startpoint = ($page * $limit) - $limit;


$Date_start = getParam('Date_start');
$Date_end = getParam('Date_end');
$Request_status = getParam('Request_status');
$PRNo = getParam('PRNo');
$processsDepartment = getParam('processsDepartment');

if ($Date_start != '' && $Date_end != '') {
    $WhereClause.= " AND rm.REQUISITION_DATE BETWEEN '$Date_start' AND '$Date_end'";
}

if ($Request_status != '') {
    $WhereClause.= " AND rm.REQUISITION_STATUS_ID='$Request_status'";
}

if ($PRNo != '') {
    $WhereClause.=" AND rm.REQUISITION_NO='$PRNo'";
}

if ($processsDepartment != '') {
    $WhereClause.=" AND rm.PROCESS_DEP_ID='$processsDepartment'";
}




$details_link = array();
$ActionLink = array('RequisitionApproval', 'RequisitionEdit');






/*
 *  eita diye (nicher) ensure kora hosse je lower level er sobai approve korse kina??? mane ekhon kon level 
 * er approval er jonno prostut. 0 hole sobai approve kore dise ar baki 1,2,3,.. er khetre approval level respectively 1,2,3,..
 */
$WorkSQL = "SELECT IFNULL(MIN(WORKFLOW_PROCESS_TYPE_ID),0) AS workflow_type FROM requisition_flow_list WHERE APPROVE_STATUS=0 GROUP BY REQUISITION_ID";
$WorkflowTypeID = query($WorkSQL);
$WorkFlowIDs = '';
while ($Row = fetch_object($WorkflowTypeID)) {
    $WorkFlowIDs.=$Row->workflow_type . ',';
}

$IDs = substr($WorkFlowIDs, 0, -1);
$IDs = $IDs == '' ? 0 : $IDs;

 $grid_sql_query = "SELECT  rm.REQUISITION_ID, rm.REQUISITION_NO,  
    br.BRANCH_DEPT_NAME,
    CONCAT(e.FIRST_NAME,' ', e.LAST_NAME,' -',e.CARD_NO, ' (', d.DESIGNATION_NAME,')') AS 'Requisition_from',
    p.PRIORITY_NAME, rs.STATUS_NAME,
    rm.REQUISITION_DATE, 
    rm.CREATED_BY,
    (
        CASE WHEN USER_LEVEL_ID='$UserLevelId' THEN  
        (SELECT ul.USER_LEVEL_NAME FROM user_level ul WHERE ul.USER_LEVEL_ID='$UserLevelId')
        ELSE 
            (SELECT CONCAT(ep.FIRST_NAME,' ',ep.LAST_NAME, '->', ep.CARD_NO, ' (',d.DESIGNATION_NAME,')' ) FROM employee ep WHERE EMPLOYEE_ID=rm.PRESENT_LOCATION_ID)
        END
    ) AS 'PresentLocation', pd.PROCESS_DEPT_NAME

    FROM requisition As rm 
    LEFT JOIN employee AS e ON e.EMPLOYEE_ID=rm.CREATED_BY
    LEFT JOIN branch_dept As br ON br.BRANCH_DEPT_ID= e.BRANCH_DEPT_ID
    LEFT JOIN priority AS p ON p.priority_id=rm.PRIORITY_ID
    
    LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
    LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=rm.PROCESS_DEPT_ID
    LEFT JOIN requisition_status rs ON rs.REQUISITION_STATUS_ID=rm.REQUISITION_STATUS_ID
    
    WHERE  (rm.PRESENT_LOCATION_ID='$employeeId' OR rm.USER_LEVEL_ID='$UserLevelId') 
        AND rm.REQUISITION_STATUS_ID IN (1,2,3) AND rm.ISCANCELLED<>1
    GROUP BY rm.REQUISITION_ID ORDER BY rm.REQUISITION_ID DESC ";

$RequisitionList = query($grid_sql_query);


//}

$StatusList = rs2array(query("SELECT REQUISITION_STATUS_ID, STATUS_NAME FROM requisition_status"));
$processsDepartmentList = rs2array(query("SELECT REQUISITION_ROUTE_ID, ROUTE_NAME FROM requisition_route"));


?>
<br/>
<div Title='Requisition Pending' class="easyui-panel" style="height:1000px;" >
    <form action="" method="GET" name='RequisitionListSearch'> 
        <fieldset class="fieldset">
            <legend>Search Form</legend>
            <table>
                <tr>
                    <td width="150">Start Date:</td>
                    <td width="150"><input type="text" name="Date_start" class="date" value="<?php echo $Date_start; ?>"/></td>
                    <td width="200">End Date:</td>
                    <td width="150"><input type="text" name="Date_end" class="date" value="<?php echo $Date_end; ?>"/></td>
                    <td>Status:</td>
                    <td><?php comboBox('Request_status', $StatusList, $Request_status, TRUE) ?></td>
                </tr>
                <tr>
                    <td>PR No:</td>
                    <td><input type="text" name="PRNo" value="<?php echo $PRNo; ?>"></td>
                    <td>Processing Department:</td>
                    <td><?php comboBox('processsDepartment', $processsDepartmentList, $processsDepartment, TRUE) ?></td>
                    <td></td>
                    <td></td>
                </tr> 
            </table>
            <button type="submit" name="save" value="SaveRequist" class="button">Search</button>
        </fieldset>
    </form>
    <br/>

    <?php //table_top("$total", $limit);  ?>

    <table class="easyui-datagrid">
        <thead>
            <tr>
                <th field="1" width="30">S/N</th>
                <th field="2" width="100">PR No.</th>
                <th field="3" width="100" align="center">Date</th>
                <th field="4">Requisition From</th>
                <th field="5" width="120">Processing Department</th>
                <th field="6" width="50">Priority</th>
                <th field="7">Present Location</th>
                <th field="9" width="80" align="center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($RowOfRequisitionList = fetch_object($RequisitionList)) {
                $processName = $RowOfRequisitionList->WORKFLOW_STATUS == '' ? 'New' : $RowOfRequisitionList->WORKFLOW_STATUS;
                ?>
                <tr>
                    <td><?php echo++$sl; ?></td>
                    <td><?php echo $RowOfRequisitionList->REQUISITION_NO; ?></td>
                    <td><?php echo bddate($RowOfRequisitionList->REQUISITION_DATE); ?></td>
                    <td><?php echo $RowOfRequisitionList->Requisition_from; ?></td>
                    <td><?php echo $RowOfRequisitionList->PROCESS_DEPT_NAME; ?></td>
                    <td><?php echo $RowOfRequisitionList->PRIORITY_NAME; ?></td>
                    <td><?php echo $RowOfRequisitionList->PresentLocation; ?> </td>
                    <td><a href='RequisitionApproval.php?mode=search&search_id=<?php echo $RowOfRequisitionList->REQUISITION_ID . '&flow_list_id=' . $RowOfRequisitionList->GP_REQUISITION_FLOW_LIST_ID; ?>'>Review</a></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php
//pagination("$total", $page, '?', "$limit");

include '../body/footer.php';
?>