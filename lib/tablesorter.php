<?php 
include('include.php');
include("../body/header.php");
include_once ('pagination.php'); 
?>	
<script type="text/javascript">
    $(document).ready(function(){
	
        $('#excel').click(function(){
            var d=$('#resultTable').table2CSV();
            window.location.href = 'xl_print.php?data='+d;
        });
        
        $(".ui-state-default").delegate("tr", "click", function() {
            $(this).addClass("even DTTT_selected").siblings().removeClass("even DTTT_selected");
        });
        
        $(".ui-state-default").tablesorter();
        $(".ui-state-default").tableFilter();

    });
</script>



<h1>Demo</h1>
<?php
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = (int) (!isset($_GET["limit"]) ? 10 : $_GET["limit"]);
$startpoint = ($page * $limit) - $limit;


//show records
$sql_query = "SELECT ci.carrier_gen_info_id AS 'ID', ci.carr_name AS 'CarrerName', 
ci.add1 AS 'Address', ci.add2, ci.add3, ci.phone AS 'Phone'

FROM carrier_gen_info AS ci 
LEFT JOIN carr_acct_type AS cat ON cat.carr_acct_type_id=ci.carr_acc_type_code 
LEFT JOIN country AS c ON c.country_id=ci.country 
LEFT JOIN time_zone AS tz ON tz.time_zone_id=ci.time_zone 
LEFT JOIN carr_op_mode AS com ON com.carr_op_mode_id=ci.op_mode_code 
LEFT JOIN carr_stat AS cs ON cs.carr_stat_id=ci.carr_stat_code";
$query = mysql_query($sql_query);
$query_result = mysql_query($sql_query);



//total Count
$q = explode('from', $sql_query);
$sql_query = "SELECT COUNT(*) as `num` FROM $q[1]";
$q = explode('LIMIT', $sql_query);
$sql_query = "$q[0]";

$row = mysql_fetch_object(mysql_query($sql_query));
$total = $row->num;
?>


<?php table_top($limit); ?>
<div class='table-worp' >
    <table id="resultTable" class="ui-state-default">
        <thead>
        <th>SI</th>
        <th>Name</th>
        <th>Firm Name</th>
        <th>Branch</th>
        <th>City</th>
        <th>Location</th>
        <th>Panel</th>
        <th>Engage</th>
        <th>Active</th>
        </thead>

        <tbody>
            <?php
            $sl = $limit * $page + 1 - $limit;
            $class = "odd";
            while ($rows = mysql_fetch_object($query_result)) {
                echo "<tr class=''>";
                echo "<td>$sl</td>";
                echo "<td><a href='lawyer_officer.php?mode=search&search_id=$rows->lawyer_officerid'>$rows->CarrerName</a></td>";
                echo "<td>$rows->carr_name</td>";
                echo "<td>$rows->Phone</td>";
                echo "<td>$rows->Address</td>";
                echo "<td>$rows->carr_name</td>";
                echo "<td>$rows->carr_name</td>";
                echo "<td>$rows->carr_name</td>";
                echo "<td>$rows->carr_name</td>";
                echo "</tr>";
                $sl++;
            }
            
            ?>
            
        </tbody>
    </table>
</div>
<?php pagination($total, $limit, $page, '?', $limit); ?>




