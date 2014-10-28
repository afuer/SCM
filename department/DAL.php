<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE DEPARTMENT_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT DEPARTMENT_ID, divi.DIVISION_NAME, DEPARTMENT_NAME, DEPARTMENT_ADDRESS, DEPARTMENT_SORT 
        FROM department dep
        LEFT JOIN division  divi ON divi.DIVISION_ID=dep.DIVISION_ID
        $res ORDER BY DEPARTMENT_SORT $limt";
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
        $res = $search == "" ? '' : " WHERE DEPARTMENT_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM department $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }

    public function getDepartmentAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT DEPARTMENT_ID, DEPARTMENT_NAME FROM department ORDER BY DEPARTMENT_NAME";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

}

?>
