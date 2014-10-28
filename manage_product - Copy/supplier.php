<?php
include '../lib/DbManager.php';


$chkproduct = getParam('chkproduct');
$product_list = getParam('product_list');
if (empty($product_list)) {
    $chkproduct2 = explode("~", $chkproduct);
    $product_list = implode(',', $chkproduct2);
} else {
    $chkproduct2 = explode(",", $product_list);
}
$supplierid = getParam('supplierid');
$mode = getParam('mode');
$new = true;
if (isSave()) {
    $name = getParam('name');
    $supplierid = prepNull($supplierid);
    $streetaddress = getParam('streetaddress');
    $city = getParam('city');
    $zipcode = getParam('zipcode');
    $email = getParam('email');
    $contact = getParam('contact');
    $vatnumber = getParam('vatnumber');
    $credit_account = prepNull(getParam('credit_account'));
    $credit_length = prepNull(getParam('credit_length'));
    $countrycode = prepNull(getParam('countrycode'));
    if (isNew()) {
        $sql = "
			insert into supplier (
				supplierid, 
				name, 
				streetaddress, 
				city, 
				zipcode, 
				email, 
				vatnumber, 
				credit_account, 
				credit_length, 
				countrycode,
				contact) 
  			values (
				$supplierid, 
				'$name', 
				'$streetaddress', 
				'$city', 
				'$zipcode', 
				'$email', 
				'$vatnumber', 
				$credit_account, 
				$credit_length, 
				'$countrycode',
				'$contact')";
        sql($sql);
        $supplierid = insert_id();

        foreach ($chkproduct2 as $key => $value) {
            mysql_query("Insert into supplier_price (supplierid, productid, price) values
			($supplierid, '$value', '0')") or die(mysql_error() . '---------51');
        }

        echo "<script type='text/javascript'>window.opener.parent.location.reload();</script>";
        echo "<script type='text/javascript'>window.close();</script>";
    } else {
        $updateSQL =
                "update supplier set
    			    name='$name',
					streetaddress='$streetaddress',
					city='$city',
					zipcode='$zipcode',
					email='$email',
					vatnumber='$vatnumber',
                    credit_account=$credit_account,					
                    credit_length=$credit_length,
                    countrycode='$countrycode',
                    contact='$contact'				
                where supplierid=$supplierid";
        sql($updateSQL);
    }
    if ($mode == 'createpayable') {
        header("Location: suppliers.php?mode=$mode");
        die;
    }
    $phonecatid_new = getParam('phonecatid_new');
    if (!isEmpty($phonecatid_new)) {
        $telephoneno_new = getParam('telephoneno_new');
        sql("insert into supplier_phone (supplierid, telephoneno, phonecatid)
			     values ($supplierid, '$telephoneno_new', $phonecatid_new)");
    }
}

$del_telephoneno = getParam('del_telephoneno');
if (!isEmpty($del_telephoneno)) {
    sql("delete from supplier_phone where supplierid=$supplierid and telephoneno='$del_telephoneno'");
}

$rec = new Dummy();
if (!isEmpty($supplierid)) {
    $selectSQL =
            "select supplierid,
		       name,
			   streetaddress,
			   city,
			   zipcode,
			   email,
			   vatnumber,
			   credit_account,
			   credit_length,
			   countrycode,
			   contact
		from supplier
		where supplierid=$supplierid";
    $rec = find($selectSQL);
    $new = false;
    $phoneNumbers = query("
		select telephoneno, cp.phonecatid, description
		from supplier_phone cp
		join phone_category c on c.phonecatid=cp.phonecatid
		where supplierid=$supplierid
		");
}


$phonecats = $db->rs2array("select phonecatid, description from phone_category");
$phonecats = array_merge(array(array('', "-- " . tr("Telephone type") . " --")), $phonecats);
$countries = $db->rs2array("select COUNTRY_ID, COUNTRY_NAME from country");
include("../body/header.php");
?>
<h2 style="color:#000066; ">Add New Supplier(s)</h2><br />
<div id="sub_menu">
    <a href="javascript:history.go(-1)" class="button"><span class = "icon leftarrow"></span> Go back </a>	
    &nbsp;&nbsp;|&nbsp;&nbsp;
    <a href="suppliers.php" class="button"> Supplier List</a>	
</div>



<form action="supplier.php" method="POST">
    <input type='hidden' name='mode' value='<?php echo $mode ?>'/>
    <table>
        <tr><td>Id:</td>
            <td>
<?php
if ($new) {
    
} else {
    echo $supplierid;
    echo "<input type='hidden' name='supplierid' value='$supplierid'/>";
}
?>
            </td>
        <tr>
            <td>Name:</td>
            <td><input type="text" name="name" value="<?php echo $rec->name ?>"/></td>
        <tr>
            <td>Address:</td>
            <td><?php textbox("streetaddress", $rec->streetaddress, 30) ?></td></tr>
        <tr>
            <td>City:</td>
            <td><?php textbox("city", $rec->city) ?></td></tr>
        <tr>
            <td>Zip code:</td>
            <td><?php textbox("zipcode", $rec->zipcode) ?></td></tr>
        <tr>
            <td>Country:</td>
            <td><?php combobox("countrycode", $countries, $rec->countrycode, true) ?></td></tr>
        <tr>
            <td>Contact:</td>
            <td><?php textbox("contact", $rec->contact, 30) ?></td></tr>
        <tr>
            <td>E-mail:</td>
            <td><?php textbox("email", $rec->email, 30) ?></td></tr>
        <tr>
            <td>Telephone numbers</td>
        </tr>
<?php
while ($row = fetch($phoneNumbers)) {
    echo "<tr>";
    echo "<td>$row->description</td>";
    echo "<td>";
    echo $row->telephoneno;
    echo "&nbsp;";
    deleteIcon("supplier.php?supplierid=$supplierid&del_telephoneno=$row->telephoneno");
    echo "</td>";
    echo "</tr>";
}
echo "<tr>";
echo "<td>";
combobox('phonecatid_new', $phonecats, null, true);
echo "</td>";
echo "<td>";
textbox('telephoneno_new', '');
echo "</td>";
echo "</tr>";

$q = query("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID = '{$_GET['productid']}'");
$d = fetch_object($q);
?>
        <tr>
            <td colspan="2"><hr></td>
        </tr>
        <tr>
            <td><b>Product name:</b></td>
            <td><?php echo $d->model; ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?php
        saveButton();
        echo "&nbsp;";
        if (!$new)
            button("Add supplier", "add", "supplier.php");
?></td>
        </tr>
    </table>
    <br/>
    <input type="hidden" name="chkproduct" value="<?php echo $chkproduct; ?>" />
    <input type="hidden" name="chkproduct2" value="<?php echo $chkproduct2; ?>" />
    <input type="hidden" name="product_list" value="<?php echo $product_list; ?>" />

    <input type="hidden" name="new" value="<?php echo $new ?>"/>
</form>
<?php include("../body/footer.php"); ?>