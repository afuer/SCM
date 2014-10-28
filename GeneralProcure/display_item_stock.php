<?php

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$offset = ($page - 1) * $rows;
$result = array();

include 'conn.php';

$rs = mysql_query("select count(*) from product");
$row = mysql_fetch_row($rs);
$result["total"] = $row[0];



$rs = mysql_query("select 
        pr.item_code,
        pr.productid, 
        pr.model,
        pr.reorder_qty, 
        pak.description,
        'link'
        from product pr 
        left join unittype pak on pak.unittype = pr.unittype
        where pr.requisition_for=0
        group by productid order by pr.model asc limit $offset,$rows");

$items = array();
while ($row = mysql_fetch_object($rs)) {
    array_push($items, $row);
}

//echo "<pre>";
$result["rows"] = $items;

//print_r($items);

echo json_encode($result);
?>