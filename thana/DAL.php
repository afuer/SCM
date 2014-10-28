<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE THANA_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT th.THANA_ID, th.THANA_NAME, th.DISTRICT_ID, dist.DISTRICT_NAME
        FROM thana th
        LEFT JOIN district  dist ON dist.DISTRICT_ID=th.DISTRICT_ID
        $res ORDER BY th.THANA_NAME $limt";
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
        $res = $search == "" ? '' : " WHERE THANA_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM thana $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }

}

?>
