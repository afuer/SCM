<?php
include '../lib/DbManager.php';
?>
<head>
<title>ICS System Solutions - <?php etr("Marketing") ?></title>

<link rel="stylesheet" type="text/css" media="all" href="../css/main.css" />
<link rel="stylesheet" type="text/css" media="all" href="notdemo.css" />
<style type='text/css'>@import url(../include/jscalendar/calendar-win2k-1.css);</style>
<script src='../include/jscalendar/calendar.js'></script>
<script src='../include/jscalendar/lang/calendar-en.js'></script>
<script src='../include/jscalendar/calendar-setup.js'></script>

</head>

<body>

<div id=main>

<div align="center">


<?php
	$q = mysql_query("Select * from mak_quot where id = '{$_GET["id"]}' and sr_id = 1") or die("View" . mysql_error());
	$d = mysql_fetch_object($q);
	
	$d_id = $d->id;
	$d_date = $d->date;
	$d_company = $d->companyname; 
	$d_name = $d->name;
	$d_address = $d->address;
	$d_tax = $d->vat;
	$d_netcost = $d->amount;
	$d_status = $d->statss;
	
	
	$qq = mysql_query("Select customerid from customer where companyname = '$d_company'");
	$dd = mysql_fetch_object($qq);
	
	
	if ($_POST["cmdapp"])
	{
		mysql_query("update mak_req set statss = 1 
				where quot_id = {$_GET["id"]}
			
			") or die("Mee " . mysql_error());
			
		mysql_query("Update mak_leads set converted = 5, status = 'Quotation approved' where comp_id = {$_POST["id"]} and sr_id = 1")or die("mysql_error()");
		
			?>
	<script type="text/javascript">
	<!--
	window.location = "mg_sales_activity.php"
	//-->
	</script>

<?php	

	} elseif ($_POST["cmdrej"]){
	die;
	
		mysql_query("update mak_req set statss = 2 
				where quot_id = {$_GET["id"]}
			
			") or die("Mee " . mysql_error());
			
			mysql_query("Update mak_leads set converted = 4, status = 'Quotation approved' where comp_id = {$_POST["id"]} and sr_id = 1")or die("mysql_error()");

	?>
	<script type="text/javascript">
	<!--
	window.location = "mg_sales_activity.php"
	//-->
	</script>

<?php	
	}
		
	
$rr = mysql_query("Select amount from mak_req where quot_id = {$_GET["id"]}") or die(mysql_error());
$r1 = mysql_fetch_object($rr);

?>
<form action="work_order.php?id=<?php echo $_GET["id"]; ?>" method="post">
<table width="100%" border="0" id="table">
  <tr>
    <td colspan="5"><h2>
		View Order
	</h2></td>
  </tr>
   <tr>
    <td colspan="5">
	<?php
echo "<p style='padding:5px; font-size:15px; color:#fff;display:block; background-color:green; text-align:center; font-weight:bold; '>Agreed Amount : Tk. $r1->amount</p>";

	?>
	</td>
  </tr>
  
  <tr>
    <td colspan="5"><h3>Quotation Details</h3></td>
  </tr>
  <tr>
    <td>
	Quotation ID:
	</td>
	<td >
		<?php echo $d_id; ?>
	</td>
	<td>
		Date:
	</td>
	<td>
	<?php echo $d_date; ?>
	
	</td>
	<td>
	</td>
  </tr>

   <tr>
    <td>
	Company:
	</td>
	<td >
		<?php echo $d_company; ?>
	</td>
	<td>
		Address:
	</td>
	<td>
		<?php echo $d_address; ?>
	</td>
	<td>
	</td>
  </tr>
   <tr>
    <td>
	Contact Person:
	</td>
	<td >
		<?php echo $d_name; ?>
	</td>
	<td>
		
	</td>
	<td>
		
	</td>
	<td>
	</td>
  </tr>
  <tr>
    <td colspan="5" align="right"></td>
  </tr>
  
  <tr>
  	<td colspan="5">
	<h3>
		Product List
	</h3>
	</td>
  </tr>
 </table>
 </form>

 <form action="work_order.php?id=<?php echo $_GET["id"]; ?>" method="post" name="prod1">
 <table width="100%" id="table">
 	<th>Sn</th>
 	<th>Product</th>
 	<th>Quantity</th>
	<th>Price</th>
	<th>Total Prices</th>
	
<?php 
$qa = mysql_query("Select * from mak_quotitems where quot_id = $d->id order by id");
while ($da = mysql_fetch_object($qa)){
$sn++;
?>
	<tr class='<?php echo $class; ?>'>
		<td width="2"><?php echo $sn; ?></td>
		<td><?php echo $da->product; ?></td>
		<td align="right"><?php echo $da->qty; ?></td>
		<td align="right"><?php echo $da->cost; ?></td>
		<td align="right"><?php echo number_format($da->totalcost, 2, '.', ','); ?></td>
	</tr>
<?php
$class = ($class == "even" ? "odd" : "even");
$grandtotal = $grandtotal + $da->totalcost;
}
?>	
<tr>
	<td colspan="5">&nbsp;</td>
</tr>
<tr >
	<td colspan=4  align=right style="border-top:4px solid #000;">
		<b>Untaxed Amount:</b>
	</td>
	<td align=right style="border-top:4px solid #000;"><b>
	<?php echo number_format($grandtotal, 2, '.', ','); ?>
	</b>
	</td>
</tr><tr >
	<td colspan=4  align=right >
		<b>Vat:</b>
	</td>
	<td align=right >
		<?php echo number_format($d_vat, 2, '.', ''); ?> %
	</td>
</tr>
<tr >
	<td colspan=4  align=right >
		<b>Total Cost:</b>
	</td>
	<td align=right >
		<?php echo number_format($d_netcost, 2, '.', ','); ?>
	</td>
</tr>
<tr >
	<td colspan=5  align=center >
	
		<input type="hidden" value ="<?php echo $dd->customerid; ?>" name="id" />
		<input type="submit" value="Process" size=10 name="cmdapp" />
		<input type="submit" value="Reject" size=10 name="cmdrej" />
		
	</td>
</tr>
 </table>
</form>
</div>
<?php endBody(); ?>
</div>
</body>
