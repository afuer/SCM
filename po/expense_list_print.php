<?php 
session_start();
	function writeXLS(){
	  require_once 'Spreadsheet/Excel/Writer.php';
	 //require_once('../conf/config.php');
	  require_once('../include/database.inc.php');
	// Creating a workbook
	$workbook = new Spreadsheet_Excel_Writer();
	// sending HTTP headers
	$workbook->send('expenselist.xls');
	// Creating a worksheet
	$worksheet =& $workbook->addWorksheet('GC Test');


	// The actual data 
	$worksheet->write(0, 0, 'S.L');
	$worksheet->write(0, 1, 'PO No');
	$worksheet->write(0, 2, 'Vendor');
	$worksheet->write(0, 3, 'WO/PO/SO Ref');
	$worksheet->write(0, 4, 'Created By');
	$worksheet->write(0, 5, 'WO Value');
	$worksheet->write(0, 6, 'Paid up to date');
	
	
	define('DBHOST', 'localhost');
	define('DBUSER', 'cblprocure');
	define('DBPWD', 'cblpr0cur3');
	define('DBNAME', 'cblprocure');
	mysql_connect(DBHOST, DBUSER, DBPWD);
	mysql_select_db(DBNAME);
	
	
	
	$get_query = $_SESSION['get_query'];

$sl =1;
$get_query_result = mysql_query($get_query) or die(mysql_error());
while($row = mysql_fetch_object($get_query_result))
{
$var = mysql_query("select 
		  (prc.quantity*prc.unitprice) as total
		  from 
		  purchaseorder_item prc
		  left join purchaseorder po on po.poid=prc.poid
		  left join product pr on pr.productid=prc.productid
		   where prc.poid='$row->poid' group by prc.productid");
$rec = mysql_fetch_object($var);
	$worksheet->write($sl, 0, $sl);
	$worksheet->write($sl, 1, trim($row->poid));
	$worksheet->write($sl, 2, trim($row->name));
	$worksheet->write($sl, 3, trim($row->supp_ref));
	$worksheet->write($sl, 4, trim($row->givenname));
	$worksheet->write($sl, 5, trim($rec->total));
	$worksheet->write($sl, 6, trim($row->total));
	
		
	$sl++;
		
	}
	//$worksheet->write(trim($sum_wo_value += $row->wo_value;));
 
	$workbook->close();
	
	}
	
    writeXLS();
?>