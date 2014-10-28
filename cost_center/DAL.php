<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE COST_CENTER_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT cc.COST_CENTER_ID,cc.COST_CENTER_NAME,cc.COST_CENTER_CODE,cc.DIVISION_ID,divi.DIVISION_NAME,
        CASE WHEN ISAVTIVE ='1' THEN 'Yes' ELSE 'No' END AS 'ISAVTIVE'
        FROM cost_center cc
        LEFT JOIN division divi ON  divi.DIVISION_ID = cc.DIVISION_ID
        $res ORDER BY COST_CENTER_NAME $limt";
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
        $res = $search == "" ? '' : "WHERE COST_CENTER_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM cost_center $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }


}

?>
