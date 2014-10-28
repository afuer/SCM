<?php

include '../lib/DbManager.php';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$offset = ($page - 1) * $rows;
$result = array();


 $requisition_sql = "SELECT r.REQUISITION_ID, r.REQUISITION_NO, br.BRANCH_DEPT_NAME, 
    CONCAT(e.FIRST_NAME,' ', e.LAST_NAME,' -',e.CARD_NO, ' (', d.DESIGNATION_NAME,')') AS 'Requisition_from',
p.PRIORITY_NAME, r.REQUISITION_DATE, rs.status_name as 'Status',r.CREATED_BY, '' AS action,
pd.PROCESS_DEPT_NAME, 
(
    CASE WHEN USER_LEVEL_ID IS NOT NULL THEN  
        (SELECT ul.USER_LEVEL_NAME FROM user_level ul WHERE ul.USER_LEVEL_ID=r.USER_LEVEL_ID)
    ELSE (
	SELECT CONCAT(e.FIRST_NAME,' ',e.FIRST_NAME, '->', e.CARD_NO, ' (', d.DESIGNATION_NAME,')' )
	FROM employee e
	INNER JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
	WHERE EMPLOYEE_ID=r.PRESENT_LOCATION_ID
    ) END
) AS 'PresentLocation', PARENT_REQUISITION_ID, rt.REQUISITION_TYPE_NAME

FROM requisition As r
LEFT JOIN branch_dept As br ON br.BRANCH_DEPT_ID= r.BRANCH_DEPT_ID
LEFT JOIN priority AS p ON p.priority_id=r.PRIORITY_ID 
LEFT JOIN requisition_status AS rs ON rs.REQUISITION_STATUS_ID=r.REQUISITION_STATUS_ID 
LEFT JOIN employee AS e ON e.EMPLOYEE_ID=r.CREATED_BY  
LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=r.PROCESS_DEPT_ID
LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID=r.REQUISITION_TYPE_ID
WHERE r.CREATED_BY='$employeeId'
ORDER BY r.REQUISITION_ID DESC
LIMIT $offset,$rows";


$sql_count = "SELECT COUNT(*) FROM requisition As r
LEFT JOIN branch_dept As br ON br.BRANCH_DEPT_ID= r.BRANCH_DEPT_ID
LEFT JOIN priority AS p ON p.priority_id=r.PRIORITY_ID 
LEFT JOIN requisition_status AS rs ON rs.REQUISITION_STATUS_ID=r.REQUISITION_STATUS_ID 
LEFT JOIN employee AS e ON e.EMPLOYEE_ID=r.CREATED_BY  
LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=r.PROCESS_DEPT_ID
WHERE r.CREATED_BY='$employeeId'";

$result["total"] = findValue($sql_count);


$rs = query($requisition_sql);
$items = array();
while ($row = mysql_fetch_object($rs)) {
    array_push($items, $row);
}

$result["rows"] = $items;


echo json_encode($result);
?>