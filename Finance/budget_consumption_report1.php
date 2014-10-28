<?php
include_once '../lib/DbManager.php';
//checkPermission(120);

include("../body/header.php");

?>
<script language="JavaScript" src="ajax.js"></script>	
<style>
    .grid_trail{
        width:2000px;
    }
    .padding_less{
        padding: 0px;
        background-color:#7070B2;

    }
</style>

<?php
$submit = getParam('submit');
$month_id = getParam('month_id');
$year_of = getParam('year_of');
$costcenter_id = getParam('costcenter_id');
$divisionid = getParam('divisionid');
if (isset($month_id)) {
    $cur_month = $month_id;
} else {
    $cur_month = date('m');
}

if (isset($year_of)) {
    $year_of = $year_of;
} else {
    $year_of = date('Y');
}
?>







<?php
$costcenter_ids = rs2array(query("select code, code, name from cost_center_code"));
$divisions = rs2array(query("SELECT divisionid, division FROM division"));
$months = rs2array(query("SELECT month_id, 	month_name  FROM months"));
$years = rs2array(query("SELECT year_name ,	year_name FROM years"));

//  $cur_month  $cur_year   // month_id  year_id  costcenter_id

function convert_month($start) {
    switch ($start) {
        case "1":
            $select_month = 'm1';
            break;
        case "2":
            $select_month = 'm2';
            break;
        case "3":
            $select_month = 'm3';
            break;
        case "41":
            $select_month = 'm4';
            break;
        case "5":
            $select_month = 'm5';
            break;
        case "6":
            $select_month = 'm6';
            break;
        case "7":
            $select_month = 'm7';
            break;
        case "8":
            $select_month = 'm8';
            break;
        case "9":
            $select_month = 'm9';
            break;
        case "10":
            $select_month = 'm10';
            break;
        case "11":
            $select_month = 'm11';
            break;
        case "12":
            $select_month = 'm12';
            break;
    }
    return $select_month;
}
?>
<?php
//echo "KKKK".$costcenter_id;

$con = 'WHERE 1 ';
$new_con = 'WHERE 1 ';

if ($costcenter_id != '') {
    $con .=" and costcenter_id='$costcenter_id'";
    $new_con .=" and costcenter_id='$costcenter_id'";
}
if ($year_of != '') {
    $con .=" AND year(settlement_date)='$year_of'";
    $new_con .=" AND year_of='$year_of'";
}
?>


<form action="" method="POST" > 
    <table width="80%" border="0"  id="hor-minimalist-b">
        <tr>
            <td><?php echo tr("Division") ?>:</td>
            <td><select name="divisionid" onChange="ajaxLoader2('ajax_costcenter.php?val='+this.value+'&action=subject','ajax_costcenter','<left><img src=ajaxLoader.gif></left>')">
                    <option></option>
                    <?php
                    $query_d = query("select divisionid, division from division");
                    while ($rec_d = fetch($query_d)) {
                        ?>
                        <option value="<?php echo $rec_d->divisionid; ?>"<?php
                    if ($divisionid == $rec_d->divisionid) {
                        echo "selected";
                    }
                        ?>><?php echo $rec_d->division; ?></option>
                                <?php
                            }
                            ?>
                </select></td>
            <td>Cost Center</td>
            <td id="ajax_costcenter"><?php comboBox('costcenter_id', $costcenter_ids, $costcenter_id, true) ?>

        </tr>
        <tr>
            <td>Reporting Month</td>  
            <td><?php comboBox('month_id', $months, $cur_month, true) ?> </td>

            <td>Year</td>
            <td><?php comboBox('year_of', $years, $year_of, true) ?></td>         
        </tr>
        <tr>
            <td>&nbsp;</td><td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" id="as" value="Submit" /></td>
        </tr>
    </table>
</form>


<table id="hor-minimalist-b" cellpadding="3" cellspacing="3" style="border-collapse: collapse" bordercolor="#111111" width="100%">
    <tr>
        <td width="25%"> <a href='../dataimport/dataimport.php'>New file upload</a></td>
        <td width="25%"><a href='../finance/budget_search.php'>View Budget</a></td>
        <td width="25%"><a href='../finance/cbl_budget.php'>Create New Budget Entry</a></td>
        <td width="25%"><a href='../finance/cbl_budget_history.php'>Budget History</a></td>
    </tr>
</table>  


<br><br> 



<?php
if ($submit == 'Submit') {
    ?>

    <b>Division Name: 
        <?php
        if (isset($divisionid)) {
            $division_name = findValue("SELECT division FROM division where divisionid='$divisionid'");
            echo $division_name;
        }
        ?> </b>
    <br><br>


    <table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse"  id="hor-minimalist-b"  id="AutoNumber1">


        <tr>
            <td width="200px" bgcolor="#000080"><font color="#FFFFFF"> Sl</font></td>

            <td width="200px" bgcolor="#000080"><font color="#FFFFFF">Cost Center</font></td>
            <td width="200px" bgcolor="#000080"><font color="#FFFFFF">Account No.</font></td>
            <td width="200px" bgcolor="#000080"><font color="#FFFFFF">Account Name</font></td>
         <!--   <td width="200px" bgcolor="#000080"><font color="#FFFFFF">Account Type</font></td>  -->
            <td width="200px" bgcolor="#000080"><font color="#FFFFFF">Year</font></td>
            <td width="80" bgcolor="#000080"><font color="#FFFFFF">Total Expense</font></td>
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">January</font></td>
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">February</font></td>
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">March</font></td>
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">April</font></td>
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">May</font></td>    
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">June</font></td>
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">July</font></td> 
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">August</font></td>
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">September</font></td>
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">October</font></td> 
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">November</font></td> 
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">December</font></td>
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">Variance (Cur. Month-Pre. Month)</font></td>
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">Var%</font></td>    
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">MTD Budget</font></td>
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">Budget Variance</font></td> 
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">Average (Start Month-Cur. Month)</font></td>
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">YTD </font></td>
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">YTD Budget</font></td> 
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">YTD Budget Variance</font></td> 
            <td width="22" bgcolor="#000080"><font color="#FFFFFF">Full Year Budget</font></td>
            <td width="15%" bgcolor="#000080"><font color="#FFFFFF">Room for next Month</font></td>       



        </tr>  
        <!-- Excel data heading  -->    
        <?php
        $sql = query("SELECT
            payment_info_details.payment_info_id,
            payment_info_details.account_id,
            expense_account.account_name,
            payment_info_details.costcenter_id, 
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =01 then payment_info_details.amount else 0 end))  as m1,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =02 then payment_info_details.amount else 0 end))  as m2,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =03 then payment_info_details.amount else 0 end))  as m3,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =04 then payment_info_details.amount else 0 end))  as m4,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =05 then payment_info_details.amount else 0 end))  as m5,  
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =06 then payment_info_details.amount else 0 end))  as m6,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =07 then payment_info_details.amount else 0 end))  as m7,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =08 then payment_info_details.amount else 0 end))  as m8,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =09 then payment_info_details.amount else 0 end))  as m9,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =10 then payment_info_details.amount else 0 end))  as m10, 
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =11 then payment_info_details.amount else 0 end))  as m11,
            sum((case when DATE_FORMAT(payment_info.settlement_date,'%m') =12 then payment_info_details.amount else 0 end))  as m12,  
            sum((payment_info_details.amount)) as expense_amount                          
            FROM
            payment_info
            INNER JOIN payment_info_details ON payment_info.payment_info_id = payment_info_details.payment_info_id
            INNER JOIN expense_account ON payment_info_details.account_id = expense_account.account_code
            $con  
            GROUP BY costcenter_id, account_id
            
      ");
        //month_id  year_id  costcenter_id     where costcenter_id=1002      $con    -- 
        $sl = 0;
        $class = "odd";
        while ($rec = fetch($sql)) {
            $sl++;

            echo "<tr>";
            echo "<td>$sl. </td>";
            echo "<td>$rec->costcenter_id </td>";
            echo "<td>$rec->account_id</td>";
            echo "<td>$rec->account_name</td>";
            //    echo "<td>Staff Cost</td>";
            echo "<td>$year_of </td>";
            echo "<td>$rec->expense_amount</td>";
            echo "<td>$rec->m1 </td>";
            echo "<td>$rec->m2 </td>";
            echo "<td>$rec->m3 </td>";
            echo "<td>$rec->m4 </td>";
            echo "<td>$rec->m5 </td>";
            echo "<td>$rec->m6 </td>";
            echo "<td>$rec->m7 </td>";
            echo "<td>$rec->m8 </td>";
            echo "<td>$rec->m9 </td>";
            echo "<td>$rec->m10 </td>";
            echo "<td>$rec->m11 </td>";
            echo "<td>$rec->m12 </td>";

            $cur_month1 = convert_month($cur_month);
            $previous_month1 = convert_month($cur_month - 1);
            $variance = $rec->$cur_month1 - $rec->$previous_month1;

            $cur_month_value = $rec->$cur_month1;

            echo "<td> $variance </td>";
            $var = ($variance / $rec->$previous_month1) * 100;
            echo "<td>$var %</td>";
            echo "<td>";

            // echo "SELECT cbl_budget.$cur_month1 FROM cbl_budget  $new_con and ac_no=$rec->account_id  and m3=$cur_month1";

            echo $sql_budget = findValue("SELECT cbl_budget.$cur_month1 FROM cbl_budget $new_con and ac_no=$rec->account_id  and m3=$cur_month1");

            echo "</td>";
            $budget_variance = $sql_budget - $cur_month_value;
            echo "<td>$budget_variance </td>";
            echo "<td>";
            if ($cur_month == 01) {
                $ydt = $rec->m1;
                echo $ydt;
            } else if ($cur_month == 02) {
                $ydt = $rec->m1 + $rec->m2;
                echo $ydt / 2;
            } else if ($cur_month == 03) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3;
                echo $ydt / 3;
            } else if ($cur_month == 04) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4;
                echo $ydt / 4;
            } else if ($cur_month == 05) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4 + $rec->m5;
                echo $ydt / 5;
            } else if ($cur_month == 06) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4 + $rec->m5 + $rec->m6;
                echo $ydt / 6;
            } else if ($cur_month == 07) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4 + $rec->m5 + $rec->m6 + $rec->m7;
                echo $ydt / 7;
            } else if ($cur_month == 08) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4 + $rec->m5 + $rec->m6 + $rec->m7 + $rec->m8;
                echo $ydt / 8;
            } else if ($cur_month == 09) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4 + $rec->m5 + $rec->m6 + $rec->m7 + $rec->m8 + $rec->m9;
                echo $ydt / 9;
            } else if ($cur_month == 10) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4 + $rec->m5 + $rec->m6 + $rec->m7 + $rec->m8 + $rec->m9 + $rec->m10;
                echo $ydt / 10;
            } else if ($cur_month == 11) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4 + $rec->m5 + $rec->m6 + $rec->m7 + $rec->m8 + $rec->m9 + $rec->m10 + $rec->m11;
                echo $ydt / 11;
            } else if ($cur_month == 12) {
                $ydt = $rec->m1 + $rec->m2 + $rec->m3 + $rec->m4 + $rec->m5 + $rec->m6 + $rec->m7 + $rec->m8 + $rec->m9 + $rec->m10 + $rec->m11 + $rec->m12;
                echo $ydt / 12;
            }
            echo "</td>";
            echo "<td>$ydt</td>";
            echo "<td>";
            if ($cur_month == 01) {
                $ytd_budget = findValue("SELECT cbl_budget.m1 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 02) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2 FROM cbl_budget $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 03) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3 FROM cbl_budget $new_con and ac_no=$rec->account_id");
                echo $ytd_budget;
            } else if ($cur_month == 04) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 05) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 06) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5+cbl_budget.m6 FROM cbl_budget $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 07) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5+cbl_budget.m6+cbl_budget.m7 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 08) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5+cbl_budget.m6+cbl_budget.m7+cbl_budget.m8 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 09) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5+cbl_budget.m6+cbl_budget.m7+cbl_budget.m8+cbl_budget.m9 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 10) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5+cbl_budget.m6+cbl_budget.m7+cbl_budget.m8+cbl_budget.m9+cbl_budget.m10 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 11) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5+cbl_budget.m6+cbl_budget.m7+cbl_budget.m8+cbl_budget.m9+cbl_budget.m10+cbl_budget.m11 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            } else if ($cur_month == 12) {
                $ytd_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5+cbl_budget.m6+cbl_budget.m7+cbl_budget.m8+cbl_budget.m9+cbl_budget.m10+cbl_budget.m11+cbl_budget.m12 FROM cbl_budget  $new_con and ac_no=$rec->account_id  ");
                echo $ytd_budget;
            }

            echo "</td>";
            $YTD_Budget_Variance = $ytd_budget - $ydt;
            echo "<td> $YTD_Budget_Variance </td>";
            echo "<td>";
            $full_year_budget = findValue("SELECT cbl_budget.m1+cbl_budget.m2+cbl_budget.m3+cbl_budget.m4+cbl_budget.m5+cbl_budget.m6+cbl_budget.m7+cbl_budget.m8+cbl_budget.m9+cbl_budget.m10+cbl_budget.m11+cbl_budget.m12 FROM cbl_budget $new_con and ac_no=$rec->account_id  ");
            echo $full_year_budget;
            echo "</td>";
            $full_year_budget_value = $full_year_budget - $ydt;
            echo "<td>$full_year_budget_value</td>";

            echo "<tr>";
        }
        ?>



    </table>

    <?php
}
?>
<!--
  <table width="80%" border="0"  id="hor-minimalist-b"> 
  <tr>
 <td> Sub Budget </td>         
 <td>  </td> 
 </tr>  
  <tr>
 <td> Brand</td> 
 </tr>  
 <tr>      
  <td> Sl. </td><td>Account ID</td>  <td>Sub Budget for </td>  <td>Amount</td> <td> Main Account ID </td>  <td> Main Account Name </td> 
 </tr>  
  <tr>      
  <td> 1. </td><td>344567788-A</td>  <td>Campain 1 </td> <td>44444.00</td>  <td> 344567788 </td>  <td> Brand Campaining </td> 
 </tr> 
  <tr>      
  <td> 2. </td><td>344567788-B</td>  <td>Campain 2</td> <td>44444.00</td>   <td> 344567788 </td>  <td> Brand Campaining</td> 
 </tr> 
  <tr>      
  <td> 3. </td><td>344567788-C</td>  <td>Campain 3 </td><td>44444.00</td>    <td> 344567788 </td>  <td> Brand Campaining </td> 
 </tr> 
  <tr>      
  <td> 4. </td><td>344567788-D</td>  <td>Campain 4</td>  <td>44444.00</td>  <td>344567788 </td>  <td> Brand Campaining </td> 
 </tr> 
 
   <tr>
 <td><?php button('Create New Sub Budget', 'Create New Sub Budge'); ?></td>         
  <td>  </td> 
 </tr>        
     
     </table>
-->
<?php include("../body/footer.php"); ?>