<?php
include '../lib/DbManager.php';


$productid = getParam('productid');
$productname = findValue("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$productid'");
$approved = getParam('approved');


if (!empty($approved)) {
    $orderids = getParam("orderids");
    $productid = getParam("productid");


    foreach ($orderids as $key => $value) {
        //	$pending = $pending_pre[$x]-$delivery_qty[$x];
        $sql = "update requisition set status_app_level=0 where productid='$productid' and REQUISITION_NO='$value'";
        sql($sql);
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";
}
include('../body/header.php');
?>




<form action="" method='GET'>
    <?php
    if (getParam("msg") != "") {
        echo "<h3 align='center'>Product has been send</h3>";
    }
    echo "<h3 align='center'>$productname</h3><br/>";
    echo "<h3 align='center'>Product Requisition Details</h3>";
    ?>

    <table class="ui-state-default">
        <thead>
            <tr>
                <th width="20" align="center">Sl.</th>
                <th width="94" align="left">Req.ID</th>
                <th align="left">Req.Person</th>
                <th width="233" align="left">Branch/ Department</th>
                <th width="131" align="center">Priority</th>
                <th width="131" align="center">Req.Qty</th>
                <th width="53" align="center">Select</th>
            </tr>
        </thead>
        <?php
        $sql = "select si.PRODUCT_ID,
	si.REQUISITION_ID,
	so.REQUISITION_NO,
	pr.PRODUCT_NAME,
	p.PRIORITY_NAME, 
	si.QTY as quantities,
        so.OFFICE_TYPE_ID,
        bd.BRANCH_DEPT_NAME,
        e.FIRST_NAME, e.LAST_NAME

        from requisition_details si
	left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
	left join requisition so on si.REQUISITION_ID=so.REQUISITION_ID
	LEFT JOIN priority p ON p.PRIORITY_ID = so.PRIORITY_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
        where so.cancelled=0 and si.DETAILS_STATUS=1 and si.status_app_level='-1'
        and si.PRODUCT_ID = '$productid' group by si.REQUISITION_ID";
////////////////status_app_level=-1  status for pendimg approval list 		
        $sql_result = query($sql);

        while ($rec = mysql_fetch_object($sql_result)) {
            $totall++;
            ?>
            <tr class='even'>
                <td><?php echo $totall; ?>.</td>
                <td align="left"><a href='../requisition/reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                    <input type='hidden' name='orderid[<?php echo $totall; ?>]' value='<?php echo $rec->REQUISITION_NO; ?>' />
                    <input type='hidden' name='product[<?php echo $totall; ?>]' value='<?php echo $rec->PRODUCT_ID; ?>' /></td> 
                <td align="left"><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME; ?></td>
                <td  align="left"><?php echo $rec->BRANCH_DEPT_NAME; ?></td>
                <td  align="center"><?php echo $rec->PRIORITY_NAME; ?></td>
                <td  align="center"><?php echo $rec->quantities; ?></td>
                <td  align="center"><input type="checkbox" name="orderids[]" id="orderids[]" value="<?php echo $rec->REQUISITION_ID; ?>"></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <p>
        <br/>
        <input type="hidden" name="productid" value="<?php echo $productid; ?>" />
        <input type="hidden" name="count" value="<?php echo $totall; ?>" />
        <input type="submit" value='Send to Normal List' class="button" name='approved' id="approved" />
        <br>
    </p>

</form>

<?php include("../body/footer.php"); ?>