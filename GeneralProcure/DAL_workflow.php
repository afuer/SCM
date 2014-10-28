<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE WORKFLOW_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT WORKFLOW_GROUP_ID, WORKFLOW_NAME
        FROM workflow_group
        $res ORDER BY WORKFLOW_NAME $limt";
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

        $rs = query("SELECT count(*) FROM workflow_group $res");
        $row = fetch_row($rs);


        return $row[0];
    }



}

?>
