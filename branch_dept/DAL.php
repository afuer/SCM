<?php

class DAL extends DbManager {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE BRANCH_DEPT_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT BRANCH_DEPT_ID, BRANCH_DEPT_NAME, ADDRESS, BRANCH_DEPT_ROUTE, BRANCH_DEPT_SORT, 
        BRANCH_DEPT_CODE, bd.OFFICE_TYPE_ID, ot.OFFICE_NAME
        FROM branch_dept bd
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=bd.OFFICE_TYPE_ID
        $res ORDER BY BRANCH_DEPT_NAME $limt";
        $sql_result = $this->query($sql);
        

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : " WHERE BRANCH_DEPT_NAME LIKE '%$search%'";
       
        $rs = $this->query("SELECT count(*) FROM branch_dept $res");
        $row = fetch_row($rs);

      

        return $row[0];
    }

    public function getBranchAll() {
        
        $sql = "SELECT BRANCH_DEPT_ID, BRANCH_DEPT_NAME FROM branch_dept ORDER BY BRANCH_DEPT_NAME";
        $result = $this->query($sql);
        
        return $result;
    }

}

?>
