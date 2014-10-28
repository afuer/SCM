<?php

include_once '../lib/DbManager.php';
//checkPermission(119);


include("../body/header.php");
?>	
<style>
    .grid_trail{
        width:1600px;
    }
    .padding_less{
        padding: 0px;
        background-color:#7070B2;

    }
</style>

<table  width ='100%' id="hor-minimalist-b" >
    <tr>
        <th><h3>CBL Budget</h3></th></tr></table>


<table width="80%" border="0"  id="hor-minimalist-b">
    <tr>
        <td>Dividion</td>
        <td><select id="courseid" name="courseid">
                <option value="251">All</option>
                <option value="251">Finance</option>
                <option value="252">Brand</option>
                <option value="270">GED</option>
                <option value="269">Legal</option>
            </select></td>
        <td>&nbsp;</td>
        <td>Cost Center</td>
        <td><select id="courseid" name="courseid">
                <option value="251">All</option>
                <option value="251">MD's Office</option>
                <option value="252">Finance</option>
                <option value="270">HRM</option>
            </select></td>
    </tr>
    <tr>
        <td>Year</td>
        <td><select id="courseid" name="courseid">
                <option value="251">2012</option>
                <option value="252">2013</option>
                <option value="270">2014</option>
            </select></td>
        <td>&nbsp;</td>
        <td>Account Type</td>
        <td><select id="courseid" name="courseid">
                <option value="251">All</option>
                <option value="251">Premise Related Expense</option>
                <option value="252">Staff Cost</option>
                <option value="270">Other Expense</option>
            </select></td>
    </tr>
    <tr>
        <td>Budget Type</td>
        <td><select id="courseid" name="courseid">
                <option value="251">All</option>
                <option value="251">OPEx</option>
                <option value="252">CAPEx</option>
            </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><input type="submit" name="as" id="as" value="Submit" /></td>
    </tr>
    <tr>
        <td><a href='../dataimport/dataimport.php'>New file upload</a></td>
        <td><a href='../finance/budget_search.php'>View Budget</a></td>
        <td><a href='../finance/cbl_budget.php?action=new'>Create New Budget Entry</a></td>  
        <td><a href='../finance/cbl_budget_history.php'>Budget History</a></td>
    </tr>
</table>  <br>  <br>
<a href=view_raw_data.php>Back</a>
<table id="hor-minimalist-b"  width ='100%' border=0>
    <th>Sl No</th>

    <th>Costcenter Id</th>
    <th>Costcenter Name</th>
    <th>AC No.</th>
    <th>Ac Name</th>
    <th>Year Of</th>
    <th>M1</th>
    <th>M2</th>
    <th>M3</th>
    <th>M4</th>
    <th>M5</th>
    <th>M6</th>
    <th>M7</th>
    <th>M8</th>
    <th>M9</th>
    <th>M10</th>
    <th>M11</th>
    <th>M12</th>
    <th>Comments</th>

    <?php

    $selectSQL = "select 	sl_no, 
           costcenter_id,
          cost_center_code.`name`,
           ac_no,
          expense_account.account_name,
          	year_of, 
          	m1, 
          	m2, 
          	m3, 
          	m4, 
          	m5, 
          	m6, 
          	m7, 
          	m8, 
          	m9, 
          	m10, 
          	m11, 
          	m12, 
          	comments
          	from cbl_budget_history
          LEFT JOIN cost_center_code ON costcenter_id = cost_center_code.`code`
          LEFT JOIN expense_account ON ac_no = expense_account.account_code
          ORDER By updated_on desc";

    $rec = query($selectSQL);

    $sl = 1;

    while ($row = fetch_object($rec)) {

        echo "<tr class=''>";
        $id = $row->sl_no;
        echo "<td>$sl</td>";

        echo "<td>$row->costcenter_id</td>";
        echo "<td>$row->name</td>";
        echo "<td>$row->ac_no</td>";
        echo "<td>$row->account_name</td>";
        echo "<td>$row->year_of</td>";
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

        $sl++;
    }
    echo "</table>";
    ?> 
<?php include("../body/footer.php"); ?>