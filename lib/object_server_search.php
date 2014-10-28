<?php
include('include.php');

//echo "<pre>";


$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
$searchTerm = $_GET['searchTerm'];
if (!$sidx)
    $sidx = 1;
if ($searchTerm == "") {
    $searchTerm = "%";
} else {
    $searchTerm = "%$searchTerm%";
}

$result = mysql_query("SELECT COUNT(*) AS count FROM master_object ");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$count = $row['count'];

if ($count > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit; // do not put $limit*($page - 1)
//echo "SELECT * FROM title WHERE name like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";

if ($total_pages != 0) {
    $SQL = "SELECT * FROM master_object WHERE object_name like '$searchTerm'  ORDER BY $sidx $sord LIMIT $start , $limit";
} else {
    $SQL = "SELECT * FROM master_object WHERE object_name like '$searchTerm'  ORDER BY $sidx $sord";
}
$result = mysql_query($SQL) or die("Couldn t execute query." . mysql_error());

$response->page = $page;
$response->total = $total_pages;
$response->records = $count;
$i = 0;
while ($row = mysql_fetch_object($result)) {
    /*
      $response->rows[$i]['id']=$row[id];
      $response->rows[$i]['cell']=array($row[id],$row[invdate],$row[name],$row[amount],$row[tax],$row[total],$row[note]);
     */
    $response->rows[$i]['object_id'] = $row->object_id;
    $response->rows[$i]['object_name'] = $row->object_name;
    $response->rows[$i]['page_title'] = $row->page_title;
    //$response->rows[$i]['author'] = $row['author'];
    //$response->rows[$i]=array($row[id],$row[invdate],$row[name],$row[amount],$row[tax],$row[total],$row[note]);
    $i++;
}
echo json_encode($response);
?>
