<?php


class ClientSidePagination extends DbManager {

    

    public function getAll($offset, $rows) {
        //$result["total"] = $this->countAll($user_name);


        $sql = "SELECT rq.REQUISITION_ID, rq.REQUISITION_NO, rq.REQUISITION_DATE, rs.status_name, 
        rt.REQUISITION_TYPE_NAME, pd.PROCESS_DEPT_NAME, rq.REQUISITION_STATUS_ID,
        (CASE WHEN USER_LEVEL_ID='10' THEN  
        (SELECT ul.USER_LEVEL_NAME FROM user_level ul WHERE ul.USER_LEVEL_ID='10')
        ELSE CONCAT(e.FIRST_NAME, ' ', e.LAST_NAME, '(',CARD_NO,')') END) AS 'PresentLocation'

        FROM requisition rq
        LEFT JOIN requisition_status rs ON rs.requisition_status_id=rq.REQUISITION_STATUS_ID
        LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID=rq.REQUISITION_TYPE_ID
        LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=rq.PROCESS_DEPT_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=rq.PRESENT_LOCATION_ID
        ORDER BY rq.REQUISITION_ID DESC LIMIT $offset, $rows";
        $sql_result = $this->query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        return json_encode($items);
    }

    

}

?>
