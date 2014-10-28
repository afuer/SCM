<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $db = new DbManager();

        $db->OpenDb();
        $res = $search == "" ? " " : " WHERE PRODUCT_BRAND_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT PRODUCT_BRAND_ID, PRODUCT_BRAND_NAME
        FROM product_brand
        $res ORDER BY PRODUCT_BRAND_NAME $limt";
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
        $res = $search == "" ? '' : "WHERE PRODUCT_BRAND_NAME LIKE '%$search%'";
        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM product_brand $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }

    public function getAllCombo() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT PRODUCT_BRAND_ID, PRODUCT_BRAND_NAME FROM product_brand ORDER BY PRODUCT_BRAND_NAME";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

}

?>
