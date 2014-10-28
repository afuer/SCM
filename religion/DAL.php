<?php

class DAL extends DbManager{

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        
        $res = $search == "" ? " " : " WHERE RELIGION_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT RELIGION_ID, RELIGION_NAME
        FROM religion
        $res ORDER BY RELIGION_NAME $limt";
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
        
        $rs = $this->query("SELECT count(*) FROM religion $res");
        $row = fetch_row($rs);

        

        return $row[0];
    }



}

?>
