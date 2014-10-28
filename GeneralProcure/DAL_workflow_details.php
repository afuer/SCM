<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE WORKFLOW_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT wgd.WORKFLOW_GROUP_DETAILS_ID, WORKFLOW_NAME, bd.BRANCH_DEPT_NAME, d.DESIGNATION_NAME,
        di.DIVISION_NAME, ot.OFFICE_NAME, pt.WORKFLOW_PROCESS_NAME
        FROM workflow_group wg
        INNER JOIN workflow_group_details wgd ON wgd.WORKFLOW_GROUP_ID=wg.WORKFLOW_GROUP_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=wgd.BRANCH_DEPT
        LEFT JOIN designation d ON d.DESIGNATION_ID=wgd.DESIGNATION_ID
        LEFT JOIN division di ON di.DIVISION_ID=wgd.DIVISION_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=wgd.OFFICE_TYPE_ID
        LEFT JOIN workflow_process_type pt ON pt.WORKFLOW_PROCESS_TYPE_ID=wgd.WORKFLOW_PROCESS_TYPE_ID

        ORDER BY WORKFLOW_NAME $limt";
        $sql_result = query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : "WHERE WORKFLOW_NAME LIKE '%$search%'";

        $rs = query("SELECT count(*) FROM workflow_group wg
        INNER JOIN workflow_group_details wgd ON wgd.WORKFLOW_GROUP_ID=wg.WORKFLOW_GROUP_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=wgd.BRANCH_DEPT
        LEFT JOIN designation d ON d.DESIGNATION_ID=wgd.DESIGNATION_ID
        LEFT JOIN division di ON di.DIVISION_ID=wgd.DIVISION_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=wgd.OFFICE_TYPE_ID
        LEFT JOIN workflow_process_type pt ON pt.WORKFLOW_PROCESS_TYPE_ID=wgd.WORKFLOW_PROCESS_TYPE_ID");
        $row = fetch_row($rs);


        return $row[0];
    }



}

?>
