<?php
include '../lib/DbManager.php';
include './manager.php';
include("../body/header.php");

$payment = getParam('payment');




$starttime1 = getParam('starttime');
$endtime1 = getParam('endtime');


$start_date = getParam('starttime');
$end_date = getParam('endtime');
$start_date = $start_date == '' ? FirstDayLastThreeMonth() : $start_date;
$end_date = $end_date == '' ? lasDayMonth() : $end_date;

$searchkey = getParam('searchkey');

$res = $searchkey == '' ? '' : " AND (sup.SUPPLIER_NAME LIKE '%$searchkey%' or emp.FIRST_NAME LIKE '%$searchkey%' OR emp.LAST_NAME LIKE '%$searchkey%') ";
  

/**
 * 
 */

$sql = "SELECT exp.PAYMENT_ID, exp.PAYMENT_NO,
        exp.CREATED_BY, po.purchase_order_id,
        CONCAT(FIRST_NAME,' ',LAST_NAME) AS givenname,
        fps.status_name, exp.`STATUS`,
        sup.SUPPLIER_NAME as beneficiary_id,
        po.purchase_order_id, po.order_no, exp.EXPENSE_DATE,
        INVOICE_NO, NET_PAY


        from fin_payment_approval_note exp
        left join supplier sup on exp.BENEFICIARY_ID=sup.SUPPLIER_ID
        left join purchase_order po on po.purchase_order_id=exp.PURCHASE_ORDER_ID
        left join employee emp on exp.CREATED_BY  = emp.EMPLOYEE_ID
        LEFT JOIN fin_payment_status fps ON fps.status_id=exp.`STATUS`
        WHERE (exp.PRENENT_LOCATION_ID='$employeeId' OR exp.USER_LEVEL_ID='$UserLevelId' OR exp.CREATED_BY='$employeeId') $res
        ORDER BY exp.PAYMENT_NO desc";

$sql_result = query($sql);
?>

<div class="panel-header">External Expense Bill List</div>  
<div style="padding: 5px 10px; background: white; min-height: 500px;" >  

    <form action="" method="GET">
        <table width="100%" class="table">
            <tr>
                <td width="100">Search Key: </td>
                <td><input type="text" name="searchkey" /></td>
            </tr>
            <tr>
                <td width="100">Date From: </td>
                <td> <input type="text" name="starttime" value="<?php formatDate($starttime); ?>" class="easyui-datebox"/></td>
            </tr>
            <tr>
                <td width="5">To:</td>
                <td><input type="text" name="endtime" value="<?php formatDate($endtime); ?>" class="easyui-datebox"/></td>
            </tr>
        </table>
        <input type="submit" name="search" value="Search" />
    </form>

    <table class="ui-state-default">
        <thead>
            <tr>
                <th field='1' width='20'>S.NO.</th>
                <th field='2' width='80'>Expense No</th>
                <th field='2' width='100'>PO No </th>
                <th field='3' width='120'>Prepare By </th>
                <th field='5' width='80'>Date</th>
                <th field='6' width="100">Invoice No</th>
                <th field='7'>Vendor</th>
                <th field='8' width="100" align="right">Amount</th>
                <th field='9' align="center">Status</th>
                <th field="10"> Action</th>
            </tr>
        </thead>
        <?php
        while ($rec = fetch_object($sql_result)) {
            $sl++;
            $link = $rec->STATUS == 4 ? "<a href='../voucher/debit_credit_voucher.php?module=Payment&id=$rec->PAYMENT_ID'>Debit/Credit Voucher</a>" :"<a href='approval_notes_view.php?expence_id=$rec->PAYMENT_ID'>Review</a>";
            ?>
            <tr>
                <td class="sn"><?php echo $sl . "."; ?></td>
                <td><a href="expense_details2.php?expence_id=<?php echo $rec->PAYMENT_ID; ?>" target="_blank"><?php echo $rec->PAYMENT_NO; ?></a></td>
                <td><a href="po_view.php?poid=<?php echo $rec->PAYMENT_ID; ?>"><?php echo $rec->order_no; ?></a></td>
                <td><?php echo $rec->givenname; ?></td>
                <td><?php echo bddate($rec->EXPENSE_DATE); ?></td>
                <td><?php echo $rec->INVOICE_NO; ?></td>
                <td  align="left"><?php echo $rec->beneficiary_id; ?></td>
                <td  align="right"><?php echo formatMoney($rec->NET_PAY); ?></td>
                <td width="10%" align="center"><?php echo $rec->status_name; ?></td>
                <td><?php echo $link; ?></td>
            </tr>
            <?php
        }
        ?>

    </table>




</div>


<?php include("../body/footer.php"); ?>