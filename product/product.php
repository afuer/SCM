<?php

class product extends DbManager {

    public function getAll($offset, $rows) {
        $result = array();
        


        $sql = "SELECT PRODUCT_ID, PRODUCT_CODE, p.DESCRIPTION, PURCHASE_PRICE, p.PRODUCT_BRAND_ID,
        PRODUCT_NAME, QTY, c.CATEGORY_NAME, sc.CATEGORY_SUB_NAME, pg.GROUP_NAME, 
        ut.UNIT_TYPE_NAME, ISACTIVE, REORDER_LEVEL, LAST_PURCHASE_PRICE, p.PRODUCT_TYPE_ID,
        DAILY_EXPENSE, LEAD_TIME, REORDER_QTY, pt.PRODUCT_TYPE_NAME,
        AT_ACTUAL, p.PRODUCT_GROUP_ID, p.CATEGORY_ID, p.CATEGORY_SUB_ID,
        p.PROCESS_DEPT_ID, pd.PROCESS_DEPT_NAME, p.UNIT_TYPE_ID, p.UNDER_SUB_CATEGORY_ID

        FROM product p
        LEFT JOIN category c ON c.CATEGORY_ID=p.CATEGORY_ID
        LEFT JOIN category_sub sc ON sc.CATEGORY_SUB_ID=p.CATEGORY_SUB_ID
        LEFT JOIN product_group pg ON pg.PRODUCT_GROUP_ID=p.PRODUCT_GROUP_ID
        LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=p.PROCESS_DEPT_ID
        LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
        LEFT JOIN product_type pt ON pt.PRODUCT_TYPE_ID=p.PRODUCT_TYPE_ID
        LEFT JOIN requisition_type rf ON rf.REQUISITION_TYPE_ID=p.PRODUCT_TYPE_ID
        ORDER BY PRODUCT_NAME LIMIT $offset, $rows";

        $result["total"] = $this->count();
        $sql_result = $this->query($sql);


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

     
        $res = '';
        $res .= $dto->ProductName == "" ? "" : " AND p.PRODUCT_NAME LIKE '%$dto->ProductName%'";
        $res .= $dto->CategoryId == "" ? "" : " AND p.CATEGORY_ID='$dto->CategoryId'";
        $res .= $dto->CategorySubId == "" ? "" : " AND p.CATEGORY_SUB_ID='$dto->CategorySubId'";
        $res .= $dto->CategoryUnderSubId == "" ? "" : " AND p.UNDER_SUB_CATEGORY_ID='$dto->CategoryUnderSubId'";
        $res .= $dto->ProcessDeptId == "" ? "" : " AND p.PROCESS_DEPT_ID='$dto->ProcessDeptId'";
        $res .= $dto->ProductTypeId == 1 ? " AND p.PRODUCT_TYPE_ID='$dto->ProductTypeId'" : "";
        $res .= $dto->ProductGroup == "" ? "" : " AND p.PRODUCT_GROUP_ID='$dto->ProductGroup'";

        $sql = "SELECT PRODUCT_ID, PRODUCT_CODE, p.DESCRIPTION, PURCHASE_PRICE, p.PRODUCT_BRAND_ID,
        PRODUCT_NAME, QTY, c.CATEGORY_NAME, sc.CATEGORY_SUB_NAME, pg.GROUP_NAME, 
        ut.UNIT_TYPE_NAME, ISACTIVE, REORDER_LEVEL, LAST_PURCHASE_PRICE, p.PRODUCT_TYPE_ID,
        DAILY_EXPENSE, LEAD_TIME, REORDER_QTY, pt.PRODUCT_TYPE_NAME,
        AT_ACTUAL, p.PRODUCT_GROUP_ID, p.CATEGORY_ID, p.CATEGORY_SUB_ID,
        p.PROCESS_DEPT_ID, pd.PROCESS_DEPT_NAME, p.UNIT_TYPE_ID, p.UNDER_SUB_CATEGORY_ID

        FROM product p
        LEFT JOIN category c ON c.CATEGORY_ID=p.CATEGORY_ID
        LEFT JOIN category_sub sc ON sc.CATEGORY_SUB_ID=p.CATEGORY_SUB_ID
        LEFT JOIN product_group pg ON pg.PRODUCT_GROUP_ID=p.PRODUCT_GROUP_ID
        LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=p.PROCESS_DEPT_ID
        LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
        LEFT JOIN product_type pt ON pt.PRODUCT_TYPE_ID=p.PRODUCT_TYPE_ID
        WHERE 1 $res ORDER BY PRODUCT_NAME LIMIT $offset, $rows";
        $sql_result = $this->query($sql);

        $items = array();
        while ($row = fetch_object($sql_result)) {
            array_push($items, $row);
        }
        $result["rows"] = $items;

        return json_encode($result);
    }

    public function count($dto = NULL) {
        $res = '';
        $res .= $dto->ProductName == "" ? "" : " AND p.PRODUCT_NAME LIKE '%$dto->ProductName%'";
        $res .= $dto->CategoryId == "" ? "" : " AND p.CATEGORY_ID='$dto->CategoryId'";
        $res .= $dto->CategorySubId == "" ? "" : " AND p.CATEGORY_SUB_ID='$dto->CategorySubId'";
        $res .= $dto->CategoryUnderSubId == "" ? "" : " AND p.UNDER_SUB_CATEGORY_ID='$dto->CategoryUnderSubId'";
        $res .= $dto->ProcessDeptId == "" ? "" : " AND p.PROCESS_DEPT_ID='$dto->ProcessDeptId'";
        $res .= $dto->ProductTypeId == 1 ? " AND p.PRODUCT_TYPE_ID='$dto->ProductTypeId'" : "";
        $res .= $dto->ProductGroup == "" ? "" : " AND p.PRODUCT_GROUP_ID='$dto->ProductGroup'";


        $rs = $this->query("SELECT count(*) FROM product p WHERE 1 $res");
        $row = fetch_row($rs);

        return $row[0];
    }

    public function getProductGroupAll() {
        $sql = "SELECT PRODUCT_GROUP_ID, GROUP_NAME FROM product_group";
        $result = $this->query($sql);

        return $result;
    }

    public function getRequisitionForAll() {
        $sql = "SELECT REQUISITION_FOR_ID, REQUISITION_FOR FROM requisition_for";
        $result = $this->query($sql);

        return $result;
    }

    public function getUnitAll() {
        $sql = "SELECT UNIT_TYPE_ID, UNIT_TYPE_NAME FROM unit_type ORDER BY UNIT_TYPE_NAME";
        $result = $this->query($sql);

        return $result;
    }

    public function getRouteAll() {
        $sql = "SELECT REQUISITION_ROUTE_ID, ROUTE_NAME FROM requisition_route ORDER BY ROUTE_NAME";
        $result = $this->query($sql);

        return $result;
    }

}

?>
