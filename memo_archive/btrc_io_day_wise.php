<?php
include('../lib/therp_include.php');
include('../body/header.php');
include ('../lib/pagination.php');
//include('../report_btrc_DAL/btrc_io_day_wise_DAL.php');
//echo Page_URL_nav("");
//$firstdaymonth = firstDayMonth();
$from_date = getParam('from_time');
$from_date = ($from_date == '') ? $firstdaymonth . ' 00:00:00' : $from_date;
$to_date = getParam('to_time');
$to_date = ($to_date == '') ? date('Y-m-d') . ' 23:59:59' : $to_date;
?>



<h1 align="center">DAY WISE INCOMING OUTGOING REPORT</h1>
<br/>
<br/>
<form method="GET">
    <fieldset>
        <legend><strong>SEARCH</strong></legend>
        <table align="center">
            <tr>                
                <td width="100">Date From:</td>
                <td><input name="from_time" type="text" class="datetimepicker_from" value="<?php echo $from_date; ?>"/></td>
                <td>Date To:</td>
                <td><input name="to_time" type="text" class="datetimepicker_to" value="<?php echo $to_date; ?>"/></td>
                <td><button name="Submit" type="submit" class="button">SUBMIT</button></td>
            </tr>   
        </table>
    </fieldset>
</form>
<br/>
<br/>

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




<br/>
<br/>        
<?php
if ($_GET) {
    $tot_call_incoming = 0;
    $tot_duration_incoming = 0;
    $tot_call_outgoing = 0;
    $tot_duration_outgoing = 0;
    $report_obj = new btrc_io_day_wise_DAL();
    $incomming_data = $report_obj->get_incomming($from_date, $to_date);
    $total = mysql_num_rows($incomming_data);
    table_top($total, $limit, '');
    ?>
    <table id="resultTable" class="ui-state-default">
        <thead>
            <tr>
                <th rowspan="2">Date </th>
                <th colspan="2">INCOMING</th>
                <th colspan="2">OUTGOING</th>
            </tr>  
            <tr>
                <th>No. of Calls</th>
                <th>Paid Minutes</th>
                <th>No. of Calls</th>
                <th>Paid Minutes</th>
            </tr>
        </thead>
        <tbody>
    <?php
    while ($row = mysql_fetch_object($incomming_data)) {
        ?>
                <tr>
                    <td><?php echo $row->i_o_date; ?></td>
                    <td align="right"><?php $tot_call_incoming+=$row->i_c;
        if ($row->i_c == '') {
            echo '0';
        }
        else echo $row->i_c; ?></td>
                    <td align="right"><?php $tot_duration_incoming+=$row->i_m;
        if ($row->i_m == '') {
            echo '0';
        }
        else echo $row->i_m; ?></td>
                    <td align="right"><?php $tot_call_outgoing+=$row->o_c;
        if ($row->o_c == '') {
            echo '0';
        }
        else echo $row->o_c; ?></td>
                    <td align="right"><?php $tot_duration_outgoing+=$row->o_m;
        if ($row->o_m == '') {
            echo '0';
        }
        else echo $row->o_m; ?></td>
                </tr>  
            <?php
        }
        ?>
        <td align="right">Total=</td>
        <td align="right"><?php echo $tot_call_incoming; ?></td>
        <td align="right"><?php echo $tot_duration_incoming; ?></td>
        <td align="right"><?php echo $tot_call_outgoing; ?></td>
        <td align="right"><?php echo $tot_duration_outgoing; ?></td>

    <?php
}
?>
</tbody>
</table>




<?php
//pagination($total, $page, '', $limit);
include('../body/footer.php');
?>
