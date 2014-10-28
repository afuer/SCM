
<?php
include('include.php');
include("../body/header.php");
$GLAccountID = getParam('gl_id');
$CostCenterID = getParam('cc_id');
$Year = date('Y');
$Month = date('m');

$GLAccountName = findValue("SELECT CONCAT(GL_ACCOUNT_NAME,' ','(',GL_ACCOUNT_CODE,')') FROM GL_ACCOUNT WHERE GL_ACCOUNT_ID='$GLAccountID'");
$CostCenterName = findvalue("SELECT COST_CENTER_NAME FROM cost_center WHERE COST_CENTER_ID='$CostCenterID'");




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
$ActualBudgetOfMonth = findValue($SQL);

$ActualBudgetOfMonth = $ActualBudgetOfMonth == '' ? 0.00 : $ActualBudgetOfMonth;

$ActualBudgetYear = findValue("SELECT ifnull(SUM(budget_amount),0) FROM actual_budget WHERE gl_account_id='$GLAccountID' AND cost_center_id='$CostCenterID' AND year='$Year'");

$AllocatedAmountForMonth = findValue("SELECT SUM(PAYABLE) 
FROM budget_allocation ba
LEFT OUTER JOIN gp_requesition gr ON gr.REQUISITION_ID=ba.REQUISITION_ID
WHERE SUBSTR(ba.MODIFY_DATE,1,4)='$Year' AND SUBSTR(ba.MODIFY_DATE,6,2)='$Month' AND EXPENCE_BUDGET_STATUS=1
AND gr.REQUISITION_STATUS_ID=8");

$AllocatedAmountForYear = findValue("SELECT SUM(PAYABLE) 
FROM budget_allocation ba
LEFT OUTER JOIN gp_requesition gr ON gr.REQUISITION_ID=ba.REQUISITION_ID
WHERE SUBSTR(ba.MODIFY_DATE,1,4)='$Year' AND EXPENCE_BUDGET_STATUS=1
AND gr.REQUISITION_STATUS_ID=8");
?>
<table>
    <tr>
        <td><b>GL :</b></td>
        <td colspan="3"><?php echo $GLAccountName; ?></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><b>Cost Center :</b></td>
        <td colspan="3"><?php echo $CostCenterName; ?></td>
        <td><b>Year :</b></td>
        <td><?php echo $Year; ?></td>
    </tr>
</table>
<br/>
<br/>
<h2>Monthly Variance :</h2>
<table class="ui-state-default" width="200">
    <thead>
    <th><?php echo $SelectedMonth; ?></th>
    <th>Country Budget</th>
</thead>
<tr>
    <td>Budget: </td>
    <td><?php echo $MonthBudget; ?></td>
</tr>
<tr>
    <td>Actual:</td>
    <td><?php echo $ActualBudgetOfMonth; ?></td>
</tr>
<tr>
    <td>Allocation:</td>
    <td><?php echo $AllocatedAmountForMonth; ?></td>
</tr>
<tr>
    <td>Variance:</td>
    <td><?php echo $b = ($ActualBudgetOfMonth - $AllocatedAmountForMonth); ?></td>
</tr>
</table>

<br />
<br />
<h2> <u>YTD Variance :</u></h2>
<table class="ui-state-default">
    <thead>
    <th><?php echo $Year; ?></th>
    <th>Country Budget</th>
</thead>
<tr>
    <td>Budget</td>
    <td><?php echo $YearlyBudget; ?></td>
</tr>
<tr>
    <td>Actual</td>
    <td><?php echo $ActualBudgetYear; ?></td>
</tr>
<tr>
    <td>Allocation</td>
    <td><?php echo $AllocatedAmountForYear; ?></td>
</tr>
<tr>
    <td>Variance</td>
    <td><?php echo $a = ($ActualBudgetYear - $AllocatedAmountForYear); ?></td>
</tr>
</table>