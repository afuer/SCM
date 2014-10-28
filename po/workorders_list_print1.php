<?php

session_start();

function writeXLS() {
    require_once 'Spreadsheet/Excel/Writer.php';
    //require_once('../conf/config.php');
    require_once('../include/database.inc.php');
    // Creating a workbook
    $workbook = new Spreadsheet_Excel_Writer();
    // sending HTTP headers
    $workbook->send('workorderlist.xls');
    // Creating a worksheet
    $worksheet = & $workbook->addWorksheet('GC Test');

    // The actual data 
    $worksheet->write(0, 0, 'S.L');
    $worksheet->write(0, 1, 'Date');
    $worksheet->write(0, 2, 'CS No');
    $worksheet->write(0, 3, 'PO ID By');
    $worksheet->write(0, 4, 'Supplier');
    $worksheet->write(0, 5, 'Delivery Date');
    $worksheet->write(0, 6, 'Created By');
    $worksheet->write(0, 7, 'WO Value');
    $worksheet->write(0, 8, 'Status');

    define('DBHOST', 'localhost');
    define('DBUSER', 'cblprocure');
    define('DBPWD', 'cblpr0cur3');
    define('DBNAME', 'cblprocure');
    mysql_connect(DBHOST, DBUSER, DBPWD);
    mysql_select_db(DBNAME);

    $get_query = $_SESSION['get_query'];
//	$get_query = $_REQUEST['get_query'];;




    $sl = 1;
    $get_query_result = mysql_query($get_query) or die(mysql_error());
    while ($row = mysql_fetch_object($get_query_result)) {

        $worksheet->write($sl, 0, $sl);
        $worksheet->write($sl, 1, trim($row->orderdate));
        $worksheet->write($sl, 2, trim($row->comparisonid));
        $worksheet->write($sl, 3, trim($row->poid));
        $worksheet->write($sl, 4, trim($row->name));
        $worksheet->write($sl, 5, trim($row->delivery_date));
        $worksheet->write($sl, 6, trim($row->givenname));
        $worksheet->write($sl, 7, trim($row->wo_value));
        $worksheet->write($sl, 8, trim($row->status));



        $sl++;
    }
    //$worksheet->write(trim($sum_wo_value += $row->wo_value;));

    $workbook->close();
}

writeXLS();
?>