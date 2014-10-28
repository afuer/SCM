
<?php
include '../lib/DbManager.php';
include("../body/header.php");


$GLAccountID = getParam('gl_id');
$CostCenterID = getParam('cc_id');
$division_id = getParam('division_id');
$sol_id = getParam('sol_id');
$year_name = getParam('year');
$year = getParam('year_of');
$month_id = getParam('month_id');

$Year = date('Y');
$Month = date('m');

$GLAccountName = findValue("SELECT CONCAT(GL_ACCOUNT_NAME,' ','(',GL_ACCOUNT_CODE,')') FROM gl_account WHERE GL_ACCOUNT_ID='$GLAccountID'");
$CostCenterName = findvalue("SELECT COST_CENTER_NAME FROM cost_center WHERE COST_CENTER_ID='$CostCenterID'");

$costCenterList = $db->rs2array("SELECT COST_CENTER_ID, COST_CENTER_CODE, COST_CENTER_NAME FROM cost_center ORDER BY COST_CENTER_NAME");
$glList = $db->rs2array("SELECT GL_ACCOUNT_ID, GL_ACCOUNT_ID, GL_ACCOUNT_NAME FROM gl_account ORDER BY GL_ACCOUNT_NAME");
$divisionList = $db->rs2array("SELECT DIVISION_ID, DIVISION_NAME FROM division ORDER BY DIVISION_NAME");
$solList = $db->rs2array("SELECT SOL_ID, SOL_CODE, SOL_NAME FROM sol ORDER BY SOL_NAME");

$monthList = array(array('JAN', 'JAN'), array('FEB', 'FEB'), array('MAR', 'MAR'), array('APR', 'APR'), array('MAY', 'MAY'), array('JUN', 'JUN'), array('JUL', 'JUL'), array('AUG', 'AUG'), array('SEP', 'SEP'), array('OCTO', 'OCTO'), array('NOV', 'NOV'), array('DECE', 'DECE'));
$year_list = array(array('2010', '2010'), array('2011', '2011'), array('2012', '2012'), array('2013', '2013'), array('2014', '2014'), array('2015', '2015'));

if ($Month == 01) {
    $SelectedMonth = 'JAN';
}
if ($Month == 02) {
    $SelectedMonth = 'FEB';
}
if ($Month == 03) {
    $SelectedMonth = 'MAR';
}
if ($Month == 04) {
    $SelectedMonth = 'APR';
}
if ($Month == 05) {
    $SelectedMonth = 'MAY';
}
if ($Month == 06) {
    $SelectedMonth = 'JUN';
}
if ($Month == 07) {
    $SelectedMonth = 'JUL';
}
if ($Month == 08) {
    $SelectedMonth = 'AUG';
}
if ($Month == 09) {
    $SelectedMonth = 'SEP';
}
if ($Month == 10) {
    $SelectedMonth = 'OCTO';
}
if ($Month == 11) {
    $SelectedMonth = 'NOV';
}
if ($Month == 12) {
    $SelectedMonth = 'DECE';
}
$SelectedMonth = $Month != '' ? '*' : $SelectedMonth;



$WhereClause = "cb.BUDGET_YEAR='$Year'";

if ($GLAccountID == '') {
    $WhereClause.=" AND cb.COSTCENTER_ID='$CostCenterID'";
}
if ($CostCenterID == '') {
    $WhereClause.=" AND cb.GL_ACCOUNT_ID='$GLAccountID'";
}

$MonthlyBudgetSQl = "SELECT $SelectedMonth FROM cbl_budget_details
LEFT OUTER JOIN cbl_budget ON cbl_budget.CBL_BUDGET_ID=cbl_budget_details.CBL_BUDGET_ID
WHERE cbl_budget.BUDGET_YEAR='$Year'";
$MonthBudget = findValue($MonthlyBudgetSQl);

$YearlyBudget = findValue("SELECT (JAN+FEB+MAR+APR+MAY+JUN+JUL+AUG+SEP+OCTO+NOV+DECE) AS YEARBUDGET 
FROM cbl_budget_details cbd
LEFT OUTER JOIN cbl_budget cb ON cb.CBL_BUDGET_ID=cbd.CBL_BUDGET_ID
WHERE $WhereClause");


$SQL = "SELECT budget_amount FROM actual_budget WHERE gl_account_id='$GLAccountID' AND cost_center_id='$CostCenterID' AND year='$Year' AND month='$Month'";
//$ActualBudgetOfMonth = findValue($SQL);

$ActualBudgetOfMonth = $ActualBudgetOfMonth == '' ? 0.00 : $ActualBudgetOfMonth;

//$ActualBudgetYear = findValue("SELECT ifnull(SUM(budget_amount),0) FROM actual_budget WHERE gl_account_id='$GLAccountID' AND cost_center_id='$CostCenterID' AND year='$Year'");

$AllocatedAmountForMonth = findValue("SELECT SUM(PAYABLE) 
FROM budget_allocation ba
LEFT OUTER JOIN requisition gr ON gr.REQUISITION_ID=ba.REQUISITION_ID
WHERE SUBSTR(ba.MODIFY_DATE,1,4)='$Year' AND SUBSTR(ba.MODIFY_DATE,6,2)='$Month' AND EXPENCE_BUDGET_STATUS=1
AND gr.REQUISITION_STATUS_ID=8");

$AllocatedAmountForYear = findValue("SELECT SUM(PAYABLE) 
FROM budget_allocation ba
LEFT OUTER JOIN requisition gr ON gr.REQUISITION_ID=ba.REQUISITION_ID
WHERE SUBSTR(ba.MODIFY_DATE,1,4)='$Year' AND EXPENCE_BUDGET_STATUS=1
AND gr.REQUISITION_STATUS_ID=8");

$res = '';
$res .= $GLAccountID == '' ? '' : " AND GL_ID='$GLAccountID'";
$res .= $CostCenterID == '' ? '' : " AND COST_CENTER_ID='$CostCenterID'";
$res .= $division_id == '' ? '' : " AND DIVISION_ID='$division_id'";
$res .= $sol_id == '' ? '' : " AND SOL_ID='$sol_id'";
$res .= $year_name == '' ? '' : " AND BUDGET_YEAR='$year_name'";




$sql = "SELECT GL_ID, 
if(cost_center_ID is null,0,SUM(TOTAL_AMOUNT)) as CC,
if(DIVISION_ID is null,0,SUM(TOTAL_AMOUNT)) as Divi,
if(SOL_ID is null,0,SUM(TOTAL_AMOUNT)) as SolB

FROM budget 
WHERE 1 $res
GROUP BY gl_ID";


$budget_result = find($sql);
?>
<div class="panel-header">Budget List</div>
<div style="background: white; padding: 10px 20px;">
    <form action="" method="GET" class="formValidate">
        <fieldset>
            <legend>Search</legend>

            <table class="table">
                <tr>
                    <td width="100"><b>GL :</b></td>
                    <td><?php comboBox('gl_id', $glList, $GLAccountID, TRUE); ?></td>
                </tr>
                <tr>
                    <td><b>Cost Center :</b></td>
                    <td><?php comboBox('cc_id', $costCenterList, $CostCenterID, TRUE); ?></td>
                </tr>
                <tr>
                    <td><b>Division :</b></td>
                    <td><?php comboBox('division_id', $divisionList, $division_id, TRUE); ?></td>
                </tr>
                <tr>
                    <td><b>Sol :</b></td>
                    <td><?php comboBox('sol_id', $solList, $sol_id, TRUE); ?></td>
                </tr>
                <tr>
                    <td><b>Month :</b></td>
                    <td><?php comboBox('month_id', $monthList, $month_id, TRUE); ?></td>
                </tr>
                <tr>
                    <td><b>Year :</b></td>
                    <td><?php comboBox('year', $year_list, $year_name, TRUE); ?></td>
                </tr>
            </table>
            <button type="submit" class="button" name="view">View</button>
        </fieldset>
    </form>

    <br/>
    <br/>
    <h2>Monthly Variance :</h2>
    <table class="ui-state-default" width="200">
        <thead>
        <th><?php echo $month_id; ?></th>
        <th>Country Budget</th>
        <th>Division Budget</th>
        <th>Cost Center Budget</th>
        <th>Sol Budget</th>
        </thead>
        <tr>
            <td>Budget: </td>
            <td><?php //echo $MonthBudget;          ?></td>
            <td><?php //echo $budget_result->Divi;          ?></td>
            <td><?php //echo $budget_result->CC;          ?></td>
            <td><?php //echo $budget_result->SolB;          ?></td>
        </tr>
        <tr>
            <td>Actual:</td>
            <td><?php //echo $ActualBudgetOfMonth;          ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Allocation:</td>
            <td><?php //echo $AllocatedAmountForMonth;          ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Variance:</td>
            <td><?php //echo $b = ($ActualBudgetOfMonth - $AllocatedAmountForMonth);          ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <br />
    <br />
    <h2><u>YTD Variance <?php echo $Year; ?>:</u></h2>

    <table class="ui-state-default">
        <thead>
        <th>Placeholder</th>
        <th>Daily Avg</th>
        <th>Monthly Avg</th>
        <th>Budget YTD</th>
        <th>YTD Expense</th>
        <th>YTD-YDD Expense</th>
        <th>Full Year-YDD Expense</th>
        <th>Full Year</th>
        </thead>
        <tbody>
            <?php
            $monthNumber = date('n');

            for ($x = 1; $x <= $monthNumber; $x++) {
                $monthName = date("M", mktime(0, 0, 0, $x, 10));
                $month.=strtoupper($monthName) . '+';
            }

            $monthSum = substr($month, 0, -1);
            $date = $Year . '-1-1';
            $month = strtoupper(date("M"));

            $resgl = '';
            $resgl .= $GLAccountID == '' ? '' : " AND db.GL_ID='$GLAccountID'";

            $sql = "SELECT gl.GL_ACCOUNT_NAME, AVG(BALANCE) AS avg, SUM(IFNULL(BALANCE,0)) AS ytdExp,
            SUM(IFNULL($monthSum,0)) AS months, SUM(IFNULL(TOTAL_AMOUNT,0)) AS TOTAL_AMOUNT
            FROM daily_budget db
            INNER JOIN budget b ON b.GL_ID=db.GL_ID
            INNER JOIN gl_account gl ON gl.GL_ACCOUNT_ID=b.GL_ID
            WHERE 1 $resgl  AND UPLOAD_DATE BETWEEN '$date'  AND CURRENT_DATE()
                    GROUP BY b.GL_ID";

            $monthlyAvgSql = "select t1.GL_ID,AVG(t1.TotalBalance) MonthlyAvgBalance 
            FROM 
            (
                select GL_ID,MONTHNAME(UPLOAD_DATE) as MName,SUM(Balance) as TotalBalance 
                from daily_budget 
                where YEAR(UPLOAD_DATE)='$year' $resgl GROUP BY GL_ID,MName) t1
                GROUP BY t1.GL_ID";

            $monthlyAvg = find($monthlyAvgSql);

            $result = sql($sql);

            while ($row = mysql_fetch_object($result)) {
                ?>
                <tr>
                    <td><?php echo $row->GL_ACCOUNT_NAME; ?></td>
                    <td><?php echo formatMoney($row->avg); ?></td>
                    <td><?php echo formatMoney($monthlyAvg->MonthlyAvgBalance); ?></td>
                    <td><?php echo formatMoney($row->TOTAL_AMOUNT); ?></td>
                    <td><?php echo formatMoney($row->months); ?></td>
                    <td><?php echo formatMoney($row->TOTAL_AMOUNT - $row->ytdExp); ?></td>
                    <td><?php echo formatMoney($row->TOTAL_AMOUNT - $row->ytdExp); ?></td>
                    <td><?php echo formatMoney($row->TOTAL_AMOUNT); ?></td>
                </tr>

                <?php
            }
            ?>
        </tbody>
    </table>

</div>
<?php include '../body/footer.php'; ?>