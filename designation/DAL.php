<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE DESIGNATION_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT DESIGNATION_ID, DESIGNATION_NAME, PRODUCT_APPROVAL, COST_APPROVAL, DESIGNATION_SORT 
            FROM designation
        $res ORDER BY DESIGNATION_SORT $limt";
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
        $res = $search == "" ? '' : " WHERE DESIGNATION_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM designation $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }
    
        public function getDesignationAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT DESIGNATION_ID, DESIGNATION_NAME FROM designation ORDER BY DESIGNATION_NAME";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

}

?>
