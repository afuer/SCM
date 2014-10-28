<?php
include_once '../lib/DbManager.php';
include '../body/header.php';
include ('../lib/pagination.php');

$whereClause = " WHERE 1";

if ($_GET) {

    $trDateFrom = getParam('tr_date_from');
    $trDateTo = getParam('tr_date_to');
    $gl = getParam('gl_search');

    if ($trDateFrom != '' && $trDateTo != '') {
        $whereClause.= " AND transaction_date BETWEEN '$trDateFrom' AND '$trDateTo'";
    }

    if ($gl != '') {
        $whereClause.= " AND account_head='$gl'";
    }
}






$gridSQL = "SELECT vat_rebate_id,
        '' AS sl,
	cb_no,
	transaction_date, 
	ga.GL_ACCOUNT_ID,
	ga.GL_ACCOUNT_NAME,
	sol, s.SOL_CODE, s.SOL_NAME,
	bill_amount,
	vat_amount,
	rebate_amount
FROM vat_rebate v
LEFT OUTER JOIN gl_account ga ON ga.GL_ACCOUNT_ID = v.account_head
LEFT JOIN sol s ON s.SOL_ID=v.sol
$whereClause ORDER BY vat_rebate_id DESC";

$resultQuery = query($gridSQL);
?>

<script type="text/javascript">

    $(document).ready(function() {
        //alert('AA');
        $('#tt').datagrid({
            onDblClickRow: function(rowIndex, rowData)
            {
                window.location.href = 'nextPage.php?id=' + rowData.MEMO_ARCHIVE_ID;
            }
        });

        $('#gl_search').combogrid({
            panelWidth: 600,
            required: true,
            url: 'gl_comboccinfo.php',
            idField: 'GL_ACCOUNT_ID',
            textField: 'GL_ACCOUNT_NAME',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'GL_ACCOUNT_ID', title: 'GL Code', width: 50},
                    {field: 'GL_ACCOUNT_NAME', title: 'GL Name', width: 50}
                ]]
        });
    });



</script>  
<script src="../public/js/table2CSV.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#excel').click(function() {
            var data = $('#resultTable').table2CSV();

            $('#data').val(data);
            //window.location.href = '../lib/xl_print.php?data='+data;
        });

        $(".ui-state-default").delegate("tr", "click", function() {
            $(this).addClass("even DTTT_selected").siblings().removeClass("even DTTT_selected");
        });

        //$("table.ui-state-default").tablesorter();
        //$("table.ui-state-default").tableFilter();
        $('#total_show').val($('#total').val());

    });
</script>

<div class="panel-header">Memo List</div>
<div style="padding: 20px 20px; background: white;">

    <form  method="GET">
        <fieldset>
            <legend>Search</legend>
            <table>
                <tr>
                    <td width='150'>Date From  :</td>
                    <td width='200'><input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" name='tr_date_from' value="<?php echo $trDateFrom; ?>"></input></td>
                    <td width='150'>Date To  :</td>
                    <td width='100'><input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" name='tr_date_to' value="<?php echo $trDateTo; ?>"></input></td>
                </tr>
                <tr>
                    <td>Account Head :</td>
                    <td><input type="text" name="gl_search" id="gl_search" value="<?php echo $gl; ?>"/></td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <input type='submit' name='search' value='search' class="button">
        </fieldset>
    </form>
    <a href="vat_rebate.php" class="button" iconCls="icon-add" plain="true">Add New</a>

    <form action="../lib/xl_print.php" method="POST">
        <input type="hidden" name="data" id="data" value=""/>
        <button type="submit" class="ui-widget-header" id="excel">Export Excel</button> 

    </form>
    <table id="resultTable" class="ui-state-default">
        <thead>  
            <tr>
                <th field="sl" width="30">SL</th>  
                <th field="cb_no" width="80">CB No.</th>  
                <th field="transaction_date" width="80">Transaction Date</th> 
                <th field="GL_ACCOUNT_CODE" width="80">Account Code</th>
                <th field="GL_ACCOUNT_NAME" width="200">Account Head</th>
                <th field="sol" width="60">SOL</th>  
                <th field="bill_amount" width="60">Bill Amount</th>  
                <th field="vat_amount" width="60">VAT Amount</th>  
                <th field="mushak_amount" width="60">Rebate Amount</th>
                <th colspan="3" width="10" align="center">ACTION</th> 
            </tr>                            
        </thead>
        <tbody>
            <?php
            while ($resultObj = fetch_object($resultQuery)) {
                $totRebateAmount+=$resultObj->rebate_amount;
                $totVATAmount+=$resultObj->vat_amount;
                $totBillAmount+=$resultObj->bill_amount;
                ?>
                <tr>  
                    <td><?php echo++$sl; ?></td>
                    <td><?php echo $resultObj->cb_no; ?></td>
                    <td><?php echo bddate($resultObj->transaction_date); ?></td>
                    <td><?php echo $resultObj->GL_ACCOUNT_ID; ?></td>
                    <td><?php echo $resultObj->GL_ACCOUNT_NAME; ?></td>
                    <td><?php echo $resultObj->SOL_CODE . '' . $resultObj->SOL_NAME; ?></td>
                    <td align="right"><?php echo formatMoney($resultObj->bill_amount); ?></td>
                    <td align="right"><?php echo formatMoney($resultObj->vat_amount); ?></td>
                    <td align="right"><?php echo formatMoney($resultObj->rebate_amount); ?></td>
                    <td><?php viewIcon("vat_rebate_view.php?searchId=$resultObj->vat_rebate_id"); ?></td>
                    <td><?php editIcon("vat_rebate.php?mode=edit&searchId=$resultObj->vat_rebate_id"); ?></td>
                    <td><?php deleteIcon("vat_rebate.php?mode=delete&searchId=$resultObj->vat_rebate_id"); ?></td>
                </tr>                            
            <?php } ?> 
        </tbody>
        <tfoot>  
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Total</b></td>
                <td align="right"><b><?php echo number_format($totBillAmount, 2); ?></b></td>
                <td align="right"><b><?php echo number_format($totVATAmount, 2); ?></b></td>
                <td align="right"><b><?php echo number_format($totRebateAmount, 2); ?></b></td>
            </tr>
        </tfoot> 

    </table>

</div>