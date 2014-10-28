<?php

class gander extends DbManager {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE GANDER_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT GANDER_ID, GANDER_NAME
        FROM gander
        $res ORDER BY GANDER_NAME $limt";
        $sql_result = $this->query($sql);
        

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : "WHERE GANDER_NAME LIKE '%$search%'";
        
        $rs = $this->query("SELECT count(*) FROM gander $res");
        $row = fetch_row($rs);

        

        return $row[0];
    }



}

?>
