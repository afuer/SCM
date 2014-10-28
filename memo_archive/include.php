<?php
//include('../lib/therp_include.php');

function makeGrid($moduleName,$width,$height) {
    
    $db = new DbManager();
    echo "<table id='dg' class=\"easyui-datagrid\" title=".$moduleName." style=\"width:".$width."px;height:".$height."px\"  
               data-options=\"singleSelect:true,collapsible:true,url:'../datagrid/datagrid_data1.json'\">";


    $gridInfoSQL = "SELECT FIELD_NAME, TITLE, SIZE FROM gridgenerator WHERE MODULE_NAME='$moduleName'";

    echo "<thead>  
                <tr>";
    $db->OpenDb();
    $obj = query($gridInfoSQL);
    $db->CloseDb();
    $i = 0;
    while ($gridInfoObj = fetch_object($obj)) {
        $fields[$i] = $gridInfoObj->FIELD_NAME;
        $i++;
        $noOfColumn = $i;
        echo "<th data-options=\"field:'" . $gridInfoObj->FIELD_NAME . "',width:" . $gridInfoObj->SIZE . "\">" . $gridInfoObj->TITLE . "</th>";
    }


    echo "</tr>";
    echo "</thead>";
    $dataSQL = "SELECT grid_sql FROM master_grid_sql WHERE grid_name='$moduleName' ";
    $db->OpenDb();
    $resultGridObj = find($dataSQL);
    $db->CloseDb();
    $sql = $resultGridObj->grid_sql;

    $newSQL = $sql;
    
    $db->OpenDb();
    $selectFromTable = query($newSQL);
    $db->CloseDb();
    while ($rowSQL = fetch_object($selectFromTable)) {

        echo "<tr>";

        for ($i = 0; $i < $noOfColumn; $i++) {

            echo "<td>" . $rowSQL->$fields[$i] . "</td>";
        }
        echo "</tr>";
    }

    echo "</tr>";
    echo "</table>";
}
?> 
<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css">  
<link rel="stylesheet" type="text/css" href="../../themes/icon.css">  
<link rel="stylesheet" type="text/css" href="../demo.css">  
<script type="text/javascript" src="../../jquery-1.8.0.min.js"></script>  
<script type="text/javascript" src="../../jquery.easyui.min.js"></script>

