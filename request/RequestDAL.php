<?php

class RequestData {

    public function SaveRequest($MaxRequestId, $RequestComment, $user_name, $RequestTypeID) {
        $SqlRequest = "INSERT INTO request_list (REQUEST_LIST_ID,REQUEST_COMMENT,REQUEST_STATUS,CREATED_BY,CREATED_DATE,REQUEST_TYPE) 
                       Values('$MaxRequestId','$RequestComment','0','$user_name',now(),'$RequestTypeID')";
        return query($SqlRequest);
    }

    function GetDataRequest($search_id) {
        echo $RequestHeading = "SELECT  rl.REQUEST_LIST_ID, rl.CREATED_BY, ed.FULL_NAME,rl.CREATED_BY,rl.CREATED_DATE,rl.REQUEST_COMMENT,rt.REQUEST_NAME
        FROM request_list AS rl 
        LEFT JOIN employee_details As ed ON ed.CARDNO = rl.CREATED_BY
        LEFT JOIN request_type As rt ON rt.REQUEST_TYPE_ID = rl.REQUEST_TYPE
        WHERE rl.REQUEST_LIST_ID = '$search_id'";
        return find($RequestHeading);
    }

    function DeletRequest($search_id) {
        $SqlDel = "DELETE FROM request_list
     WHERE REQUEST_LIST_ID ='$search_id'";
        return query($SqlDel);
    }

    function EditRequest($RequestComment,$RequestId,$RequestTypeID) {

        $SqlRequest = "UPDATE request_list SET 
                REQUEST_COMMENT = '$RequestComment',
                REQUEST_TYPE = '$RequestTypeID'
                WHERE REQUEST_LIST_ID = '$RequestId'";

      return  query($SqlRequest);
    }

}

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = (int) (!isset($_GET["limit"]) ? 20 : $_GET["limit"]);
$startpoint = ($page * $limit) - $limit;



$Date_start = getParam('Date_start');
$Date_end = getParam('Date_end');
$RequestType = getParam('RequestType');
$Request_status = getParam('Request_status');
$RequestFrom = getParam('RequestFrom');

if ($Date_start != '' && $Date_end != '') {
    $WhereClause.= " AND rl.CREATED_DATE BETWEEN '$Date_start' AND '$Date_end'";
}
if ($RequestType != '') {
    $WhereClause.= " AND REQUEST_TYPE='$RequestType'";
}
if ($Request_status != '') {
    $WhereClause.= " AND REQUEST_STATUS='$Request_status'";
}
if ($RequestFrom != '') {
    $WhereClause.=" AND rl.CREATED_BY='$RequestFrom'";
}



$RequestTypeList = rs2array(query("SELECT REQUEST_TYPE_ID, REQUEST_NAME FROM request_type ORDER BY  REQUEST_TYPE_ID"));



$request_sql = "SELECT rl.REQUEST_LIST_ID,rl.REQUEST_COMMENT,ry.REQUEST_NAME,rl.REQUEST_STATUS, ed.FULL_NAME, 
    CONCAT(rl.CREATED_BY,'-') AS CREATED_BY,rl.CREATED_DATE
FROM request_list As rl 
INNER  JOIN request_type As ry ON ry.REQUEST_TYPE_ID = rl.REQUEST_TYPE
INNER JOIN employee_details As ed ON ed.CARDNO = rl.CREATED_BY
WHERE 1 $WhereClause
ORDER BY rl.REQUEST_LIST_ID  DESC LIMIT $startpoint, $limit";
$request_result = query($request_sql);


$q = explode('FROM', $request_sql);
$sql_pa = "SELECT COUNT(*) as CoutTotal FROM $q[1]";
$q = explode('LIMIT', $sql_pa);
$sql_pa = "$q[0]";
$row = find($sql_pa);
$total = $row->CoutTotal;
?>