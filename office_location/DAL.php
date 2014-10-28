<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE OFFICE_LOCATION_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT OFFICE_LOCATION_ID, OFFICE_LOCATION_NAME
        FROM office_location
        $res ORDER BY OFFICE_LOCATION_NAME $limt";
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
        $res = $search == "" ? '' : "WHERE OFFICE_LOCATION_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM office_location $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }



}

?>
