<?php

class assignment_category {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE ASSIGNMENT_CATEGORY_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT ASSIGNMENT_CATEGORY_ID, ASSIGNMENT_CATEGORY_NAME
        FROM assignment_category
        $res ORDER BY ASSIGNMENT_CATEGORY_NAME $limt";
        $sql_result = query($sql);
        $db->CloseDb();

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : "WHERE ASSIGNMENT_CATEGORY_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM assignment_category $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }



}

?>
