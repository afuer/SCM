<?php

class ManageProduct extends DbManager {

    public function getAll($ProcessDeptId, $offset, $rows) {
        $result = array();
        $result["total"] = $this->count($ProcessDeptId);


        $sql = "SELECT so.REQUISITION_ID, so.REQUISITION_NO,
        DATE_FORMAT(so.REQUISITION_DATE,'%e %b %Y') AS REQUISITION_DATE,
        p.PRIORITY_NAME,
        CONCAT(ot.OFFICE_NAME,'->',bd.BRANCH_DEPT_NAME) AS branch_dept,
        SUM(QTY) AS QTY, SUM(DELIVERED_QTY) AS DELIVERED_QTY,
        (IFNULL(SUM(QTY),0) -IFNULL(SUM(DELIVERED_QTY),0)) AS 'pending',
        CONCAT(e.CARD_NO,'->', e.FIRST_NAME,' ',e.LAST_NAME, ' (', d.DESIGNATION_NAME,')')AS 'employeeName'

        FROM requisition so  
        INNER JOIN requisition_details rd ON rd.REQUISITION_ID=so.REQUISITION_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        LEFT JOIN priority p ON p.PRIORITY_ID=so.PRIORITY_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
        LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
        WHERE REQUISITION_TYPE_ID =1 AND so.CANCELLED=0 
        AND '$ProcessDeptId' IN (so.PROCESS_DEPT_ID)
            AND REQUISITION_STATUS_ID=3
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
            WHERE REQUISITION_TYPE_ID =1 AND so.CANCELLED=0 
            AND so.PROCESS_DEPT_ID = '$ProcessDeptId' AND REQUISITION_STATUS_ID=3";

        $rs = $this->query($sql);
        $row = fetch_row($rs);


        return $row[0];
    }

    public function getPendingAll($ProcessDeptId, $offset, $rows) {
        $result = array();
        $result["total"] = $this->CountPending($ProcessDeptId);


        $sql = "SELECT pr.PRODUCT_CODE,
        si.PRODUCT_ID,
        si.REQUISITION_ID,
        max(so.PRIORITY_ID) as priorityid,
        si.status_app_level,
        pr.PRODUCT_NAME,
        sum(si.QTY) as quantity,
        sum(si.DELIVERED_QTY) as deliverd,
        '' AS 'Pending',
        (select sum(QTY) as stock from stockmove
	WHERE PRODUCT_ID=pr.PRODUCT_ID group by PRODUCT_ID) AS 'stock',
        (select sum(delivery_qty) as allocated from app_product_delivery_history
	WHERE product_id=pr.PRODUCT_ID AND challan_id IS NULL group by product_id) AS 'allocated',
        '' AS 'available'



        FROM product pr
        LEFT JOIN requisition_details si on si.PRODUCT_ID=pr.PRODUCT_ID
        LEFT JOIN requisition so on so.REQUISITION_ID= si.REQUISITION_ID 

        WHERE REQUISITION_TYPE_ID =1 AND so.REQUISITION_STATUS_ID=5 AND si.STATUS_APP_LEVEL = 1 
        AND pr.PRODUCT_TYPE_ID=1 AND so.PROCESS_DEPT_ID='$ProcessDeptId' AND so.cancelled=0 
        GROUP BY pr.PRODUCT_ID having quantity-deliverd > 0 ORDER BY si.REQUISITION_ID LIMIT $offset, $rows";
        $sql_result = $this->query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function CountPending($ProcessDeptId) {


        $sql = "SELECT COUNT(*), sum(dh.deliverd) as deliverd,
            sum(si.QTY) as quantity 
            from product pr
            left join requisition_details si on si.PRODUCT_ID=pr.PRODUCT_ID
            left join requisition so on so.REQUISITION_ID= si.REQUISITION_ID 

            left join (
            select req_id, product_id, sum(delivery_qty) as deliverd
            from app_product_delivery_history
            group by req_id, product_id
            ) dh on dh.req_id=si.REQUISITION_ID and dh.product_id=si.PRODUCT_ID


            WHERE REQUISITION_TYPE_ID =1 and dh.deliverd IS NOT NULL  and si.QTY > dh.deliverd and si.status_app_level > 0 
            AND pr.PRODUCT_TYPE_ID=1 AND so.PROCESS_DEPT_ID='$ProcessDeptId' AND so.cancelled=0 
            GROUP BY pr.PRODUCT_ID having quantity-deliverd > 0";

        $rs = $this->query($sql);
        $row = fetch_row($rs);


        return $row[0];
    }

}

?>
