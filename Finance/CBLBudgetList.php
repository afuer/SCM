<?php
include '../lib/DbManager.php';
include("../body/header.php");

$Year = getParam('year_of');
$AccountID = getParam('gl_id');
$cc = getParam('costcenter_id');


$years = array(array('2010', '2010'), array('2011', '2011'), array('2012', '2012'), array('2013', '2013'), array('2014', '2014'), array('2015', '2015'));
$accounts = rs2array(query("SELECT GL_ACCOUNT_ID,CONCAT(GL_ACCOUNT_NAME,' ','(',GL_ACCOUNT_CODE,')') FROM GL_ACCOUNT"));
$costcenters = rs2array(query("SELECT COST_CENTER_ID,COST_CENTER_NAME FROM cost_center"));

$res = '1';
if ($cc != '') {
    $res.=" AND cb.COSTCENTER_ID='$cc'";
}
if ($Year != '') {
    $res.="AND cb.BUDGET_YEAR='$Year'";
}
if ($AccountID != '') {
    $res.="AND cb.GL_ACCOUNT_ID='$AccountID'";
}
$BudgetSQL = "SELECT cb.CBL_BUDGET_ID,BUDGET_YEAR,COST_CENTER_NAME,GL_ACCOUNT_NAME,BUDGET_STATUS,(JAN+FEB+MAR+APR+MAY+JUN+JUL+AUG+SEP+OCTO+NOV+DECE) AS BUDGET_OF_YEAR 
    FROM cbl_budget cb
    LEFT OUTER JOIN cbl_budget_details cbd ON cbd.CBL_BUDGET_ID= cb.CBL_BUDGET_ID
    LEFT OUTER JOIN cost_center cc ON  cc.COST_CENTER_ID=cb.COSTCENTER_ID
    LEFT OUTER JOIN gl_account ga ON ga.GL_ACCOUNT_ID=cb.GL_ACCOUNT_ID
    WHERE $res";

$QueryResult = query($BudgetSQL);
?>
<div Title='Requisition List' class="easyui-panel" style="height:1000px;" >
    <form method="GET">
        <table>
            <tr>
                <td>Year :</td>
                <td><?php comboBox("year_of", $years, $Year, TRUE); ?></td>
            </tr>
            <tr>
                <td>GL Account :</td>
                <td><?php comboBox("ac_no", $accounts, $AccountID, TRUE); ?></td>
                <td> Cost Center: </td>
                <td><?php comboBox("costcenter_id", $costcenters, $cc, TRUE); ?></td>
            </tr>
            <tr><td><input type="submit" name="save" value="SEARCH" class="button"></td></tr>
        </table>   
    </form>
    <a href="CBLBudgetNew.php" class="button">Add New</a>
    <a href="budget_upload.php" class="button">Upload Budget</a>

    <table class="ui-state-default">
        <thead>
        <th colspan="2"></th>
        <th colspan="12" align="center">MONTHS</th>
        </thead>
        <thead>
        <th>SL</th>
        <th>YEAR</th>
        <th>COST CENTER </th>
        <th>GL ACCOUNT </th>
        <th>BUDGET</th>
        <th>STATUS</th>
        </thead>
        <?php
        $sl = 0;
        while ($Obj = fetch_object($QueryResult)) {
            ?>
            <tr>
                <td align="center"><?php echo++$sl; ?></td>
                <td align="center"><?php echo $Obj->BUDGET_YEAR; ?></td>
                <td><?php echo $Obj->COST_CENTER_NAME; ?></td>
                <td><?php echo $Obj->GL_ACCOUNT_NAME; ?></td>
                <td align="right"><a href='CBLBudgetView.php?mode=view&cbl_budget_id=<?php echo $Obj->CBL_BUDGET_ID; ?>'><?php echo $Obj->BUDGET_OF_YEAR; ?></a></td>
                <td><?php echo $Obj->BUDGET_STATUS; ?></td>
            </tr>
        <?php }
        ?>
    </table>
</div>
<?php include("../body/footer.php"); ?>