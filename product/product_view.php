<?php

include_once '../lib/DbManager.php';
include '../body/header.php';
$object_name = 'product';
$mode = getParam('mode');
$search_id = getParam('search_id');

$object_name_id = $object_name . '_id';
$vsarSql = "SELECT PRODUCT_ID, PRODUCT_CODE, p.DESCRIPTION, MCODE, PURCHASE_PRICE,
        PRODUCT_NAME, QTY, c.CATEGOTY_NAME, sc.SUB_CATEGOTY_NAME, pg.GROUP_NAME, 
        rrt.REQUISITION_ROUTE_TYPE_NAME, ps.PACK_NAME, ut.UNIT_TYPE_NAME, ISACTIVE, REORDER_LEVEL, 
        DAILY_EXPENSE, LEAD_TIME, REORDER_QTY, OSCOMMERCEID, FREE, pt.PRODUCT_TYPE_NAME,
        AT_ACTUAL, rf.REQUISITION_FOR

        FROM product p
        LEFT JOIN category c ON c.CATEGORY_ID=p.CATEGORY_ID
        LEFT JOIN sub_category sc ON sc.SUB_CATEGORY_ID=p.SUB_CATEGORY_ID
        LEFT JOIN product_group pg ON pg.PRODUCT_GROUP_ID=p.PRODUCT_GROUP_ID
        LEFT JOIN requisition_rout_type rrt ON rrt.REQUISITION_ROUTE_TYPE_ID=p.REQUISITION_ROUTE_ID
        LEFT JOIN packsize ps ON ps.PACKSIZE_ID=p.PACKSIZE_ID
        LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
        LEFT JOIN product_type pt ON pt.PRODUCT_TYPE_ID=p.PRODUCT_TYPE_ID
        LEFT JOIN requisition_for rf ON rf.REQUISITION_FOR_ID=p.REQUISITION_FOR
        WHERE p.PRODUCT_ID='$search_id'";

$db = new DbManager();
$db->OpenDb();
include '../lib/master_page_view.php';
$db->CloseDb();
?>