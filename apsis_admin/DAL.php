<?php

class DAL {

    public function getDataGrid($offset, $rows, $search) {
        $result = array();
        $result["total"] = $this->count($search);

        $res = $search == "" ? " " : " WHERE COUNTRY_NAME LIKE '%$search%'";
        $limt = $search == '' ? " LIMIT $offset, $rows" : '';

        $sql = "SELECT sp.SYS_MENU_ID, sp.LINKS, sp._SUBID, 
        sm._SHOW, sm._SORT, sp.MENU_NAME, sp._GROUP, 
        (CASE WHEN sm.MENU_NAME IS NULL THEN 'Primary' ELSE sm.MENU_NAME END) AS 'Parent' 

        FROM sys_menu sm
        RIGHT JOIN sys_menu sp ON sm.SYS_MENU_ID=sp._SUBID
        $res ORDER BY MENU_NAME $limt";
        $sql_result = query($sql);


        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($search) {
        $res = $search == "" ? '' : "WHERE MENU_NAME LIKE '%$search%'";

        $rs = query("SELECT count(*) FROM sys_menu $res");
        $row = fetch_row($rs);
        return $row[0];
    }

}

?>
