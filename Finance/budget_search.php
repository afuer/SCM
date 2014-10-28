<?php
include_once '../lib/DbManager.php';
//checkPermission(74);

$perpage = 20;
$page = getParam('page');
$page = ($page == '') ? 0 : $page;
$page = $page * $perpage;

$Division_list = rs2array(query('SELECT DIVISION_ID, DIVISION_NAME FROM division ORDER BY DIVISION_NAME'));
/*



  $delete_budget_cc = getParam('delete_budget_cc');
  $delete_budget = getParam('delete_budget');
  $year_of = getParam('year_of');
  if (isset($delete_budget)) {

  // echo "$delete_budget_cc";
  $delete_cc = "DELETE FROM `cbl_budget`
  WHERE `cbl_budget`.`costcenter_id` = '$delete_budget_cc'
  AND `year_of` = '$year_of'";

  // echo $delete_cc;
  sql($delete_cc);
  }


  $division_id = getParam('division_id');
  $division_id = getParam('divisionid');
  $costcenter_id = getParam('costcenter_id');

  if (isset($year_of)) {
  $year_of = $year_of;
  } else {
  $year_of = date('Y');
  }

  $division_name = findValue("SELECT division FROM `division` where divisionid='$division_id'");
 * 
 */
?>	


<style>
    .grid_trail{
        width:1900px;
    }
    .padding_less{
        padding: 0px;
        background-color:#7070B2;
    }

</style>
<?php
/*
  $cond = 'WHERE 1 ';

  if ($costcenter_id != '') {
  $cond .=" AND costcenter_id='$costcenter_id'";
  }
  if ($year_of != '') {
  $cond .=" AND year_of='$year_of'";
  }

  $costcenter_ids = rs2array(query("select code, code, name from cost_center_code"));
  //	$divisions = rs2array(query("SELECT divisionid, division FROM division"));
  $years = rs2array(query("SELECT year_name, year_name FROM years"));


  $sql = "select division_id, costcenter_id, ac_no, expense_account.account_name, cost_center_code.`name`, year_of,
  (m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12) as total_budget, m1, 2, m3, m4, m5, m6, m7, m8, m9, m10, m11, m12, comments

  from cbl_budget
  LEFT JOIN cost_center_code ON cbl_budget.costcenter_id = cost_center_code.`code`
  LEFT JOIN expense_account ON cbl_budget.ac_no = expense_account.account_code
  $cond LIMIT $page, $perpage";



 */

include("../body/header.php");
?>
<form action="" method="POST"> 
    <table width="80%" border="0"  id="hor-minimalist-b">
        <tr>
            <td><?php echo tr("Division") ?>:</td>
            <td><?php comboBox2('divisionid', $Division_list, $divisionid, TRUE, '', 'ajax_costcenter'); ?></td>
            <td>&nbsp;</td>  
            <td>Cost Center</td>
            <td id="ajax_costcenter"> <?php comboBox('costcenter_id', $costcenter_ids, $costcenter_id, true) ?></td>
        </tr>
        <tr>
            <td>Year</td>
            <td><?php comboBox('year_of', $years, $year_of, true) ?></td>
            <td>&nbsp;</td> 


            <td> </td>
            <td> </td>
        </tr>
        <tr>
            <td> </td>
            <td> </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="submit" name="as" id="as" value="Submit" /></td>
        </tr>

    </table>   
</form>



<table id="hor-minimalist-b" cellpadding="3" cellspacing="3" style="border-collapse: collapse" bordercolor="#111111" width="80%">
    <tr>
        <td width="25%"> <a href='../dataimport/dataimport.php'>New file upload</a></td>
        <td width="25%"><a href='../finance/budget_search.php'>View Budget</a></td>
        <td width="25%"><a href='../finance/cbl_budget.php'>Create New Budget Entry</a></td>
        <td width="25%"><a href='../finance/cbl_budget_history.php'>Budget History</a></td>
        <td width="25%"> </td>
    </tr>
</table>  


<br><br> 
<h3>Division Name: <?php echo $division_name; ?></h3>
<?php
$rec = query($sql);
$TotalRows = num_rows($rec);

$link = "?";
Paging($link, $TotalRows, $perpage);
?>
<table id="hor-minimalist-b">
    <tr>
        <th width="25">Sl</th> 
        <th>Cost Center</th> 
        <th width="200">Cost Center Name</th> 
        <th>Account No.</th> 
        <th>Account Name</th> 
        <th>Year</th> 
        <th>Total Budget</th> 
        <th>January</th> 
        <th>February</th> 
        <th>March</th> 
        <th>April</th> 
        <th>May</th>    
        <th>June</th> 
        <th>July</th> 
        <th>August</th> 
        <th>September</th> 
        <th>October</th> 
        <th>November</th> 
        <th>December</th> 
        <th>Comments</th> 
    </tr>  
    <?php
    $sl = 1;

    while ($row = fetch_object($rec)) {

        echo "<tr>";
        echo "<td>$sl</td>";
        echo "<td>$row->costcenter_id</td>";
        echo "<td  style='width: 200px;'>$row->name</td>";
        echo "<td><a href='cbl_budget.php?view=final&&costcenter_id=$row->costcenter_id&ac_no=$row->ac_no&year_of=$row->year_of''>$row->ac_no</a></td>";
        echo "<td>$row->account_name</td>";
        echo "<td>$row->year_of</td>";
        echo "<td>$row->total_budget</td>";
        echo "<td>$row->m1</td>";
        echo "<td>$row->m2</td>";
        echo "<td>$row->m3</td>";
        echo "<td>$row->m4</td>";
        echo "<td>$row->m5</td>";
        echo "<td>$row->m6</td>";
        echo "<td>$row->m7</td>";
        echo "<td>$row->m8</td>";
        echo "<td>$row->m9</td>";
        echo "<td>$row->m10</td>";
        echo "<td>$row->m11</td>";
        echo "<td>$row->m12</td>";
        echo "<td>$row->comments</td>";
        echo "</tr>";

        $sl++;
    }
    ?>  
</table>

<?php
if (isset($costcenter_id)) {
    ?><form action="" method="POST"> 
        <input type="submit" name="delete_budget" id="as" value="Delete Budget" /> 
        <input type="hidden" name="delete_budget_cc" id="as" value="<?php echo $costcenter_id; ?>" /> 
        <input type="hidden" name="year_of" id="as" value="<?php echo $year_of; ?>" /> 

    </form>
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