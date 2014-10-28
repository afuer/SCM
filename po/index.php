<?php
include '../lib/DbManager.php';

$sql = "SELECT prm.comparisonid, prm.approved,
        emp.FIRST_NAME, emp.LAST_NAME, 
        prm.date, 
        sta.status,
        pr_de.supplier_id,
        pr_de.unite_price,
        pr_de.quantity, s.SUPPLIER_NAME,
        pr_de.quantity*pr_de.unite_price AS total_value

        from price_comparison prm
        
        INNER JOIN requisition_approval ra ON ra.CS_ID=prm.comparisonid
        left join price_comparison_details pr_de on prm.comparisonid = pr_de.comparison_id
        left join product p on p.PRODUCT_ID = pr_de.productid
        left join employee emp on prm.createby = emp.CARD_NO
        left join location_status sta on prm.status = sta.statusid 
        LEFT JOIN supplier s ON s.SUPPLIER_ID=pr_de.supplier_id
				
        WHERE pr_de.selected=1 AND ra.`STATUS`=10
        group by prm.comparisonid, pr_de.supplier_id
        order by prm.comparisonid desc";
$query_com = query($sql);

include "../body/header.php";
?>

<div class="easyui-layout" style="width:950px; height:700px; margin: auto;">  
    <div title="Purchase Order Create Able CS list" data-options="region:'center'" class="easyui-panel" >  
        <form action="comparative_supplier.php?productid=<?php echo $_REQUEST['txtproductid']; ?>" method='POST'>
            <table class="easyui-datagrid">
                <thead>
                    <tr>
                        <th field='1' width='30'>SL.</th>
                        <th field='2' width='100'>Date</th>
                        <th field='3' width='100' align="center">Comparison No</th>
                        <th field='4'>Supplier</th>
                        <th field='5' width='150' align="right">Value</th>
                        <th field='6' width='100' align="center">Action</th>
                    </tr>
                </thead>
                <?php
                while ($rec_com = fetch_object($query_com)) {
                    $sl++;
                    ?>
                    <tr>
                        <td><?php echo $sl; ?></td>
                        <td><?php echo bddate($rec_com->date); ?></td>
                        <td><a href="../manage_product/evaluation_statement.php?comparison_id=<?php echo $rec_com->comparisonid; ?>"><?php echo evaluation_no($rec_com->comparisonid); ?></a></td>
                        <td><?php echo $rec_com->SUPPLIER_NAME; ?></td>
                        <td><?php echo number_format($rec_com->total_value, 2, '.', ', '); ?></td>
                        <td><div align="center"><a href="temp_view.php?id=1&comparisonid=<?php echo $rec_com->comparisonid; ?>&supplier_id=<?php echo $rec_com->supplier_id; ?>">Create PO</a></div></td>
                    </tr>  
                <?php } ?>
            </table>
            <table width="100%">

            </table>

            <br/>
            <input type="hidden" name="parent_url" id="parent_url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
        </form>

    </div>
</div>
<?php include("../body/footer.php"); ?>