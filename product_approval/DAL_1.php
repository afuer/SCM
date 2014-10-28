<?php

class DAL extends DbManager {

    public function getDataGrid($offset, $rows, $search, $div) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE RELIGION_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT DELEGATION_AUTHORITY_ID, da.DESIGNATION_ID, da.PROCESS_DEPT_ID, 
            CONCAT(bd.BRANCH_DEPT_NAME ,'->',d.DESIGNATION_NAME) AS LD,
            pd.PROCESS_DEPT_NAME, da.PROCESS_DEPT_ID, APPROVAL_LIMIT,
            da.OFFICE_TYPE_ID, da.BRANCH_DEPT_ID

            FROM delegation_authority da
            LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=da.PROCESS_DEPT_ID
            LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=da.OFFICE_TYPE_ID
            LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=da.BRANCH_DEPT_ID
            LEFT JOIN designation d ON d.DESIGNATION_ID=da.DESIGNATION_ID
            ORDER BY pd.PROCESS_DEPT_NAME, APPROVAL_LIMIT";
        $sql_result = $this->query($sql);


        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : "WHERE RELIGION_NAME LIKE '%$search%'";

        $rs = $this->query("SELECT count(*) FROM delegation_authority da
            LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=da.PROCESS_DEPT_ID
            LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=da.OFFICE_TYPE_ID
            LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=da.BRANCH_DEPT_ID
            LEFT JOIN designation d ON d.DESIGNATION_ID=da.DESIGNATION_ID");
        $row = fetch_row($rs);



        return $row[0];
    }

}

?>
