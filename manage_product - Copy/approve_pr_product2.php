<?php
include '../lib/DbManager.php';
include('../body/header.php');

$productid = getParam('productid');
$productname = findValue("SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID='$productid'");
$approved = getParam('approved');


if (!empty($approved)) {
    $orderids = getParam("orderids");
    $productid = getParam("productid");


    foreach ($orderids as $key => $value) {
        $sql = "update requisition_details set STATUS_APP_LEVEL=-1, DETAILS_STATUS=1 where PRODUCT_ID='$productid' and REQUISITION_ID='$value'";
        sql($sql);
    }
    echo "<script type='text/javascript'>window.opener.parent.location.reload()</script>";
    echo "<script type='text/javascript'>window.close()</script>";
}
?>


<div class="easyui-panel" title="Grade List" style="width:auto; height:500px; padding:10px;">  

    <form action="" method='GET'>
        <?php
        if (getParam("msg") != "") {
            echo "<h3 align='center'>Product has been send</h3>";
        }
        ?>
        <h3 align='center'><?php echo stripslashes($productname); ?></h3><br/>
        <h3 align='center'>Product Requisition Details</h3>

        <table class="ui-state-default">
            <thead>
            <th>Sl.</th>
            <th>Req.ID</th>
            <th width="227" align="left">Req.Person</th>
            <th width="233" align="left">Branch/ Department</th>
            <th width="131" align="center">Priority</th>
            <th width="131" align="center">Req.Qty</th>
            <th width="53" align="center">Select</th>
            </thead>
            <?php
            $sql = query("SELECT si.PRODUCT_ID,
        si.REQUISITION_ID,
        so.REQUISITION_NO,
        pr.PRODUCT_NAME,
        p.PRIORITY_ID, 
        si.QTY as quantities, e.CARD_NO, so.CREATED_BY,
        bd.BRANCH_DEPT_NAME, ot.OFFICE_NAME,
        e.FIRST_NAME, e.LAST_NAME,
        sum(si.QTY) as quantities,
        (
            SELECT SUM(cs_qty) FROM price_comparison_pro_req_qty pc 
            WHERE requisition_id=si.REQUISITION_ID AND product_id=si.PRODUCT_ID
        ) AS CS_QTY


        FROM requisition_details si
        left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
        left join requisition so on so.REQUISITION_ID=si.REQUISITION_ID
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
        LEFT JOIN priority p ON p.PRIORITY_ID = so.PRIORITY_ID
        LEFT JOIN employee e ON e.CARD_NO=so.CREATED_BY
        where so.CANCELLED=0 and si.DETAILS_STATUS=3 and si.STATUS_APP_LEVEL='1'
        and si.PRODUCT_ID = '$productid' and si.QTY>0 group by si.REQUISITION_ID");
////////////////status_app_level=-1  status for pendimg approval list 	
//pr.PROCESS_DEPT_ID=1 AND 	
//$sql = query();

            while ($rec = fetch_object($sql)) {
                $totall++;
         
                ?>
                <tr>
                    <td><?php echo $totall; ?>.</td>
                    <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                        <input type='hidden' name='product[<?php echo $totall; ?>]' value='<?php echo $rec->PRODUCT_ID; ?>' /></td> 
                    <td align="left"><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . ' (' . $rec->CARD_NO . ')'; ?></td>
                    <td  align="left"><?php echo $rec->OFFICE_NAME . '->' . $rec->BRANCH_DEPT_NAME; ?></td>
                    <td  align="center"><?php echo $rec->PRIORITY_ID; ?></td>
                    <td  align="center"><?php echo $rec->quantities- $rec->CS_QTY; ?></td>
                    <td  align="center"><input type="checkbox" name="orderids[<?php echo $totall; ?>]"  value="<?php echo $rec->REQUISITION_ID; ?>"/></td>
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
</div>
<?php include("../body/footer.php"); ?>