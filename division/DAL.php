<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE DIVISION_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT DIVISION_ID,DIVISION_NAME,DIVISION_ADDRESS,DIVISION_SORT FROM division
        $res ORDER BY DIVISION_SORT $limt";
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
        $res = $search == "" ? '' : " WHERE DIVISION_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM division $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }
    
    public function getDivisionAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT DIVISION_ID, DIVISION_NAME FROM division ORDER BY DIVISION_SORT";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

}

?>
