<?php

class DAL extends DbManager {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE CATEGORY_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT CATEGORY_ID, CATEGORY_NAME, DESCRIPTION 
        FROM category
        $res ORDER BY CATEGORY_NAME $limt";
        $sql_result = $this->query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : "WHERE CATEGORY_NAME LIKE '%$search%'";
        
        $rs = $this->query("SELECT count(*) FROM category $res");
        $row = fetch_row($rs);

      

        return $row[0];
    }

    public function getAllCombo() {
        
        $sql = "SELECT CATEGORY_ID, CATEGORY_NAME FROM category ORDER BY CATEGORY_NAME";
        $result = $this->query($sql);
       

        return $result;
    }

}

?>
