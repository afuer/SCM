<?php
include '../lib/DbManager.php';
$statusids = array("", "0", "1", "2", "3", "4", "5");
$status_name = array("", "New", "Recommended", "Approved", "Processing", "Prepared", "Deliverd");


$employeeid = $userName;


include("../body/header.php");
?>

<script language="JavaScript" src="ajax.js"></script>
<style type="text/css" title="currentStyle">
    @import "../media/css/demo_page.css";
    @import "../media/css/demo_table_jui.css";
    @import "../media/jquery-ui-1.8.4.custom.css";
</style>		

<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        oTable = $('#example').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers"
        });
    });
</script>
<div id="sub_menu">
    <a href="javascript:history.go(-1)" class="button"><span class = "icon leftarrow"></span> Go back </a>
</div>
<br /><h1 style="color:#000066; ">Expense Bills</h1> <br />
<?php
$processing = getParam("processing");
$prepared = getParam("prepared");

$expence_id = getParam("expence_id");
$expence_id2 = getParam("expence_id2");

if (!empty($processing)) {
    foreach ($expence_id as $key => $value) {
        $sql = "update expence_bill set status=3, process_by ='$employeeid', processed_date=now() where expence_id=$value ";
        sql($sql);
    }
}
?>

<form action="">
    <table  cellpadding="0" cellspacing="0" border="0" class="display"  id="hor-minimalist-b" width=100%>
        <thead>
            <tr>
                <th width='5%'>Sl</th>
                <th width='12%'>Expense No </th>
                <th width='20%'>Date</th>
                <th width='18%'>Processing By </th>
                <th width='12%'>Invoice No </th>
                <th width="20%">Vendor</th>
                <th  align="right">Amount</th>
                <th align="right">Status</th>

            </tr>

        </thead>
        <tbody>
            <?php
// status 3 is processing
// satus 5 prepaired       exp.expence_no,



            $sql = query("select exp.expence_id, 	
							DATE_FORMAT(exp.date_time,'%e-%b, %Y') as date_time,
							exp.process_by, 
							exp.attachment,
							exp.status,    
							sup.name as beneficiary_id,
							expdet.invoice_no,
							expdet.amount,
							expdet.invoice_date
							from expence_bill exp
							left join employee emp on exp.prepared_by=emp.employeeid
							left join supplier sup on exp.beneficiary_id=sup.supplierid
							left join expence_bill_details expdet on exp.expence_id=expdet.expence_id
							where exp.expense_type='E' and expdet.amount IS NOT NULL and sup.name IS NOT NULL 
              and 	employee_id='$employeeid'
              order by  exp.expence_id desc
							");
            $class = "odd";
            while ($rec = fetch($sql)) {
                $sl++;
                ?>
                <tr  <?php echo "class='$class'"; ?>>
                    <td class="sn"><?php echo $sl; ?></td>
                    <td><a href="#" onclick="window.open('../expense/expense_details2.php?expence_id=<?php echo $rec->expence_id; ?>', 'popup', 'width=750,height=500,scrollbars=no,scrollbars=yes,toolbar=no,directories=no,location=no,menubar=1,status=no,left=300,top=110');
            return false"><?php echo $rec->expence_id; ?></a></td>
                    <td><?php echo $rec->date_time; ?></td>
                    <td><?php echo findValue("select givenname from employee where employeeid=$rec->process_by"); ?></td>
                    <td><?php echo $rec->invoice_no; ?></td>
                    <td><?php echo $rec->beneficiary_id; ?></td>
                    <td align="right"><?php echo $rec->amount; ?></td>
                    <td  align="center"><?php echo str_replace($statusids, $status_name, $rec->status); ?></td>

                </tr> 
                <?php
                $class = ($class == "odd" ? "even" : "odd");
            }
            ?> 
        </tbody>

    </table>
    <br/>
    <a href='expense_bill_prepared.php'>expense_bill_prepared</a>
</form>

<?php include("../body/footer.php"); ?>