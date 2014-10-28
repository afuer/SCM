<?php
include '../lib/DbManager.php';
include "../body/header.php";


$sql_produc_list = "SELECT spa.IT_APPROVAL_ID, SUM(IFNULL(QTY,0)*IFNULL(UNIT_PRICE,0)) AS total, spa.APPROVE_NO,
CONCAT(e.FIRST_NAME,' ',e.LAST_NAME,'->', e.CARD_NO) AS empName, DATE(spa.APPROVAL_DATE) AS APPROVAL_DATE
FROM it_store_product_approval spa
INNER JOIN employee AS e ON e.EMPLOYEE_ID=spa.CREATED_BY
INNER JOIN requisition_details rd ON rd.IT_APPROVAL_ID=spa.IT_APPROVAL_ID
GROUP BY spa.IT_APPROVAL_ID ORDER BY spa.IT_APPROVAL_ID DESC";

$sql = query($sql_produc_list);
?>
<div class="panel-header">Store Item Approved List</div>  
<div style="background-color:white; padding: 20px 20px; "> 
    <a href="approve_store_it_product_list_details.php" class="button">Search List</a>

    <form name="frm" action="" method='POST' autocomplete="off" class="formValidate">
        <table class="ui-state-default">
            <thead>
            <th width="20">SL.</th>
            <th width="20">Approval No</th>
            <th width="100">Date</th>
            <th>Req.Person</th>
            <th width="50">Price </th>
            <th width="50">Action</th>
            </thead>
            <tbody>

                <?php
                while ($rec = fetch_object($sql)) {
                    ?>

                    <tr class="datagrid-row">
                        <td><?php echo++$sl; ?>.</td>
                        <td><?php echo $rec->APPROVE_NO; ?></td> 
                        <td align="left"><?php echo $rec->APPROVAL_DATE; ?></td> 
                        <td><?php echo $rec->empName; ?></td>
                        <td align='right'><?php echo formatMoney($rec->total); ?></td>
                        <td align="left"><a href='approve_store_it_product_list_details.php?itApprovalId=<?php echo $rec->IT_APPROVAL_ID; ?>' target="_blank"> View</a></td> 
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!--<input type="submit" class="button" value='Submit' name='approved' id="approved" />-->


    </form>
</div>  

<?php include '../body/footer.php'; ?>