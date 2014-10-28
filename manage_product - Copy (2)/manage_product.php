<?php

class ManageProduct extends DbManager {

    public function getAll($ProcessDeptId, $offset, $rows) {
        $result = array();
        $result["total"] = $this->count($ProcessDeptId);


        $sql = "SELECT so.REQUISITION_ID, so.REQUISITION_NO,
        DATE_FORMAT(so.REQUISITION_DATE,'%e %b %Y') AS REQUISITION_DATE,
        p.PRIORITY_NAME,
        CONCAT(ot.OFFICE_NAME,'->',bd.BRANCH_DEPT_NAME) AS branch_dept,
        SUM(rd.QTY) AS QTY, SUM(DELIVERED_QTY) AS DELIVERED_QTY,
        (IFNULL(SUM(rd.QTY),0) -IFNULL(SUM(DELIVERED_QTY),0)) AS 'pending',
        CONCAT(e.CARD_NO,'->', e.FIRST_NAME,' ',e.LAST_NAME, ' (', d.DESIGNATION_NAME,')')AS 'employeeName',
        rs.STATUS_NAME, pr.PROCESS_DEPT_ID
        
        FROM requisition so  
        INNER JOIN requisition_details rd ON rd.REQUISITION_ID=so.REQUISITION_ID
        LEFT JOIN product pr ON pr.PRODUCT_ID=rd.PRODUCT_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        LEFT JOIN priority p ON p.PRIORITY_ID=so.PRIORITY_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
        LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
        LEFT JOIN requisition_status rs ON so.REQUISITION_STATUS_ID=rs.REQUISITION_STATUS_ID
        
        WHERE REQUISITION_TYPE_ID =1 AND so.CANCELLED=0 
        AND so.PROCESS_DEPT_ID LIKE '%$ProcessDeptId%' AND so.REQUISITION_STATUS_ID=3 AND rd.DETAILS_STATUS=1
        GROUP BY so.REQUISITION_ID ORDER BY so.REQUISITION_ID DESC LIMIT $offset, $rows";
        $sql_result = $this->query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($ProcessDeptId) {


        $sql = "SELECT count(*) 
            FROM requisition so  
            INNER JOIN requisition_details rd ON rd.REQUISITION_ID=so.REQUISITION_ID
            LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
            LEFT JOIN priority p ON p.PRIORITY_ID=so.PRIORITY_ID
            LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
            LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
            LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
            LEFT JOIN requisition_status rs ON so.REQUISITION_STATUS_ID=rs.REQUISITION_STATUS_ID
            WHERE REQUISITION_TYPE_ID =1 AND so.CANCELLED=0 
            AND so.PROCESS_DEPT_ID = '$ProcessDeptId' AND so.REQUISITION_STATUS_ID=3 AND rd.DETAILS_STATUS=2";

        $rs = $this->query($sql);
        $row = fetch_row($rs);


        return $row[0];
    }

    public function getPendingAll($ProcessDeptId, $offset, $rows) {
        $result = array();
        $result["total"] = $this->CountPending($ProcessDeptId);


        $sql = "SELECT so.REQUISITION_ID, so.REQUISITION_NO,
        DATE_FORMAT(so.REQUISITION_DATE,'%e %b %Y') AS REQUISITION_DATE,
        p.PRIORITY_NAME,
        CONCAT(ot.OFFICE_NAME,'->',bd.BRANCH_DEPT_NAME) AS branch_dept,
        SUM(QTY) AS QTY, SUM(DELIVERED_QTY) AS DELIVERED_QTY,
        (IFNULL(SUM(QTY),0) -IFNULL(SUM(DELIVERED_QTY),0)) AS 'pending',
        CONCAT(e.CARD_NO,'->', e.FIRST_NAME,' ',e.LAST_NAME, ' (', d.DESIGNATION_NAME,')')AS 'employeeName',
        rs.STATUS_NAME

        FROM requisition so  
        INNER JOIN requisition_details rd ON rd.REQUISITION_ID=so.REQUISITION_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        LEFT JOIN priority p ON p.PRIORITY_ID=so.PRIORITY_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
        LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
        LEFT JOIN requisition_status rs ON so.REQUISITION_STATUS_ID=rs.REQUISITION_STATUS_ID
        WHERE REQUISITION_TYPE_ID =1 AND so.CANCELLED=0 
        AND so.PROCESS_DEPT_ID IN ($ProcessDeptId) AND so.REQUISITION_STATUS_ID=3 AND rd.DETAILS_STATUS=2
        GROUP BY so.REQUISITION_ID ORDER BY so.REQUISITION_ID DESC LIMIT $offset, $rows";
        $sql_result = $this->query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function CountPending($ProcessDeptId) {


        $sql = "SELECT COUNT(*)FROM requisition so  
        INNER JOIN requisition_details rd ON rd.REQUISITION_ID=so.REQUISITION_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        LEFT JOIN priority p ON p.PRIORITY_ID=so.PRIORITY_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
        LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
        LEFT JOIN requisition_status rs ON so.REQUISITION_STATUS_ID=rs.REQUISITION_STATUS_ID
        WHERE REQUISITION_TYPE_ID =1 AND so.CANCELLED=0 
        AND so.PROCESS_DEPT_ID IN ($ProcessDeptId) AND so.REQUISITION_STATUS_ID=3 AND rd.DETAILS_STATUS=3
        GROUP BY so.REQUISITION_ID";

        $rs = $this->query($sql);
        $row = fetch_row($rs);


        return $row[0];
    }

}

?>
