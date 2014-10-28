<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE DISTRICT_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT DISTRICT_ID,DISTRICT_NAME 
        FROM district dist
        $res ORDER BY DISTRICT_NAME $limt";
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
        $res = $search == "" ? '' : " WHERE DISTRICT_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM district $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }
    
    public function getDistrictAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT DISTRICT_ID, DISTRICT_NAME FROM district ORDER BY DISTRICT_NAME";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

}

?>
