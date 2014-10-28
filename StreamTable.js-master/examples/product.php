<?php

class product {

    public function getAll($offset, $rows) {
        //echo "ddddddddd";
        $result = array();
        $result["total"] = $this->count();

        $db = new DbManager();

        $db->OpenDb();


        $sql = "SELECT PRODUCT_ID, PRODUCT_CODE, p.DESCRIPTION, PURCHASE_PRICE, p.PRODUCT_BRAND_ID,
        PRODUCT_NAME, QTY, c.CATEGORY_NAME, sc.CATEGORY_SUB_NAME, pg.GROUP_NAME, 
        rrt.REQUISITION_ROUTE_TYPE_NAME, ut.UNIT_TYPE_NAME, ISACTIVE, REORDER_LEVEL, 

        DAILY_EXPENSE, LEAD_TIME, REORDER_QTY, pt.PRODUCT_TYPE_NAME,
        AT_ACTUAL, rf.REQUISITION_FOR, p.PRODUCT_GROUP_ID, p.CATEGORY_ID, p.CATEGORY_SUB_ID,
        p.REQUISITION_ROUTE_ID, p.UNIT_TYPE_ID, p.REQUISITION_FOR

        FROM product p
        LEFT JOIN category c ON c.CATEGORY_ID=p.CATEGORY_ID
        LEFT JOIN category_sub sc ON sc.CATEGORY_SUB_ID=p.CATEGORY_SUB_ID
        LEFT JOIN product_group pg ON pg.PRODUCT_GROUP_ID=p.PRODUCT_GROUP_ID
        LEFT JOIN requisition_route_type rrt ON rrt.REQUISITION_ROUTE_TYPE_ID=p.REQUISITION_ROUTE_ID
        LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
        LEFT JOIN product_type pt ON pt.PRODUCT_TYPE_ID=p.PRODUCT_TYPE_ID
        LEFT JOIN requisition_for rf ON rf.REQUISITION_FOR_ID=p.REQUISITION_FOR
        ORDER BY PRODUCT_NAME LIMIT $offset, $rows";
        $sql_result = query($sql);
        $db->CloseDb();

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function search($offset, $rows, $dto) {
        $result = array();
        $result["total"] = $this->count($dto);

        $db = new DbManager();

        $db->OpenDb();
        $res = '';
        $res .= $dto->productName == "" ? "" : " AND p.PRODUCT_NAME LIKE '%$dto->productName%'";
        $res .= $dto->categoryId == "" ? "" : " AND p.CATEGORY_ID='$dto->categoryId'";
        $res .= $dto->categorySubId == "" ? "" : " AND p.CATEGORY_SUB_ID='$dto->categorySubId'";
        $res .= $dto->requisitionFor == "" ? "" : " AND p.REQUISITION_ROUTE_ID='$dto->requisitionFor'";

        $sql = "SELECT PRODUCT_ID, PRODUCT_CODE, p.DESCRIPTION, PURCHASE_PRICE,
        PRODUCT_NAME, QTY, c.CATEGORY_NAME, sc.CATEGORY_SUB_NAME, pg.GROUP_NAME, 
        rrt.REQUISITION_ROUTE_TYPE_NAME, ut.UNIT_TYPE_NAME, ISACTIVE, REORDER_LEVEL, 

        DAILY_EXPENSE, LEAD_TIME, REORDER_QTY, pt.PRODUCT_TYPE_NAME,
        AT_ACTUAL, rf.REQUISITION_FOR

        FROM product p
        LEFT JOIN category c ON c.CATEGORY_ID=p.CATEGORY_ID
        LEFT JOIN category_sub sc ON sc.CATEGORY_SUB_ID=p.CATEGORY_SUB_ID
        LEFT JOIN product_group pg ON pg.PRODUCT_GROUP_ID=p.PRODUCT_GROUP_ID
        LEFT JOIN requisition_route_type rrt ON rrt.REQUISITION_ROUTE_TYPE_ID=p.REQUISITION_ROUTE_ID
        LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
        LEFT JOIN product_type pt ON pt.PRODUCT_TYPE_ID=p.PRODUCT_TYPE_ID
        LEFT JOIN requisition_for rf ON rf.REQUISITION_FOR_ID=p.REQUISITION_FOR
        WHERE 1 $res ORDER BY PRODUCT_NAME LIMIT $offset, $rows";
        $sql_result = query($sql);
        
        $db->CloseDb();

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($dto = NULL) {
        $res = '';
        $res .= $dto->productName == "" ? "" : " AND p.PRODUCT_NAME LIKE '%$dto->productName%'";
        $res .= $dto->categoryId == "" ? "" : " AND p.CATEGORY_ID='$dto->categoryId'";
        $res .= $dto->categorySubId == "" ? "" : " AND p.CATEGORY_SUB_ID='$dto->categorySubId'";
        $res .= $dto->requisitionFor == "" ? "" : " AND p.REQUISITION_ROUTE_ID='$dto->requisitionFor'";


        $db = new DbManager();
        $db->OpenDb();
        $rs = query("SELECT count(*) FROM product p WHERE 1 $res");
        $row = fetch_row($rs);

        $db->CloseDb();

        return $row[0];
    }

    public function getProductGroupAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT PRODUCT_GROUP_ID, GROUP_NAME FROM product_group";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

    public function getRequisitionForAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT REQUISITION_FOR_ID, REQUISITION_FOR FROM requisition_for";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

    public function getUnitAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT UNIT_TYPE_ID, UNIT_TYPE_NAME FROM unit_type ORDER BY UNIT_TYPE_NAME";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

    public function getRouteAll() {
        $db = new DbManager();
        $db->OpenDb();
        $sql = "SELECT REQUISITION_ROUTE_ID, ROUTE_NAME FROM requisition_route ORDER BY ROUTE_NAME";
        $result = query($sql);
        $db->CloseDb();

        return $result;
    }

}

?>
