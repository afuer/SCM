<?php
include_once '../lib/DbManager.php';
include("../body/header.php");
//checkPermission(72);

$search_id = getParam('budget_id');

$m1 = getParam('m1');
$m2 = getParam('m2');
$m3 = getParam('m3');
$m4 = getParam('m4');
$m5 = getParam('m5');
$m6 = getParam('m6');
$m7 = getParam('m7');
$m8 = getParam('m8');
$m9 = getParam('m9');
$m10 = getParam('m10');
$m11 = getParam('m11');
$m12 = getParam('m12');


$years = array(array('2010', '2010'), array('2011', '2011'), array('2012', '2012'), array('2013', '2013'), array('2014', '2014'), array('2015', '2015'));
$accounts = rs2array(query("SELECT GL_ACCOUNT_ID,CONCAT(GL_ACCOUNT_NAME,' ','(',GL_ACCOUNT_CODE,')') FROM GL_ACCOUNT"));
$costcenters = rs2array(query("SELECT COST_CENTER_ID,COST_CENTER_NAME FROM cost_center"));
$this_year = date('Y');

$costcenter_id = getParam('costcenter_id');
$ac_no = getParam('ac_no');
$year_of = getParam('year_of');


$SelectSQL = "SELECT cb.CBL_BUDGET_ID,BUDGET_YEAR,COST_CENTER_NAME,GL_ACCOUNT_NAME,BUDGET_STATUS,BUDGET_YEAR,JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCTO,NOV,DECE,
    (JAN+FEB+MAR+APR+MAY+JUN+JUL+AUG+SEP+OCTO+NOV+DECE) AS TOTAL_YEAR_BUDGET
    FROM cbl_budget cb
    LEFT OUTER JOIN cbl_budget_details cbd ON cbd.CBL_BUDGET_ID= cb.CBL_BUDGET_ID
    LEFT OUTER JOIN cost_center cc ON  cc.COST_CENTER_ID=cb.COSTCENTER_ID
    LEFT OUTER JOIN gl_account ga ON ga.GL_ACCOUNT_ID=cb.GL_ACCOUNT_ID WHERE cb.CBL_BUDGET_ID='$search_id'";
$ResultObj = find($SelectSQL);


if (isSave()) {


    $UpdateMainSQL = "update cbl_budget
            		set
            		costcenter_id='$costcenter_id', 
            		gl_account_id='$ac_no', 
            		budget_year='$year_of',
                        BUDGET_COMMENT='$comments',
                        MODIFY_BY='$user_name',
                        MODIFY_DATE='now()'
                        where cbl_budget_id='$search_id'";
    query($UpdateMainSQL);


    $UpdateDetailsSQL = "update cbl_budget_details
            		set
            		JAN='$m1', 
            		FEB='$m2', 
            		MAR='$m3', 
            		APR='$m4', 
            		MAY='$m5', 
            		JUN='$m6', 
            		JUL='$m7', 
            		AUG='$m8', 
            		SEP='$m9', 
            		OCTO='$m10', 
            		NOV='$m11', 
            		DECE='$m12' 
            	where CBL_BUDGET_DETAILS_ID='$search_id'";
    query($UpdateDetailsSQL);

    echo "<script>location.replace('CBLBudgetView.php?mode=view&cbl_budget_id=$search_id');</script>";
}

?>
<script language="javascript">
    function numbersonly(e, decimal) {
        var key;
        var keychar;

        if (window.event) {
            key = window.event.keyCode;
        }
        else if (e) {
            key = e.which;
        }
        else {
            return true;
        }
        keychar = String.fromCharCode(key);

        if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27)) {
            return true;
        }
        else if ((("0123456789").indexOf(keychar) > -1)) {
            return true;
        }
        else if (decimal && (keychar == ".")) {
            return true;
        }
        else
            return false;
    }


</script>
<div Title='Payment List' class="easyui-panel" style="height:1000px;" >
    <form action="" method='POST'>
        <table>
            <tr>
                <td><?php etr("Cost Center:") ?>
                </td>
                <td colspan="3"><?php comboBox("costcenter_id", $costcenters, $costcenter_id, false) ?></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Account No.</td>
                <td colspan=3><?php comboBox("ac_no", $accounts, $ac_no, false) ?>
                </td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td>Budget Year:</td>
                <td><?php comboBox("year_of", $years, $year_of, false) ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>

        <table class="ui-state-default">
            <thead>
            <th align="center" colspan="4">MONTHS</th>
            </thead>
            <tr>
                <td width="150">January :</td>
                <td><input type='text' value='<?php echo $ResultObj->JAN; ?>' name="m1"></td>
                <td width="150">February :</td>
                <td><input type='text' value='<?php echo $ResultObj->FEB; ?>' name="m2"></td>
            </tr>
            <tr>
                <td>March :</td>
                <td><input type='text' value='<?php echo $ResultObj->MAR; ?>' name="m3"></td>
                <td>April :</td>
                <td><input type='text' value='<?php echo $ResultObj->APR; ?>' name="m4"></td>
            </tr>
            <tr>
                <td>May :</td>
                <td><input type='text' value='<?php echo $ResultObj->MAY; ?>' name="m5"></td>
                <td> 	June :</td>
                <td><input type='text' value='<?php echo $ResultObj->JUN; ?>' name="m6"></td>
            </tr>
            <tr>
                <td>July :</td>
                <td><input type='text' value='<?php echo $ResultObj->JUL; ?>' name="m7"></td>
                <td>August ;</td>
                <td><input type='text' value='<?php echo $ResultObj->AUG; ?>' name="m8"></td>
            </tr>
            <tr>
                <td>September :</td>
                <td><input type='text' value='<?php echo $ResultObj->SEP; ?>' name="m9"></td>
                <td>October :</td>
                <td><input type='text' value='<?php echo $ResultObj->OCTO; ?>' name="m10"></td>
            </tr>
            <tr>
                <td>November :</td>
                <td><input type='text' value='<?php echo $ResultObj->NOV; ?>' name="m11"></td>
                <td>December :</td>
                <td><input type='text' value='<?php echo $ResultObj->DECE; ?>' name="m12"></td>
            </tr>
        </table>
        <input type="submit" class="button" name="save" value="Save"/>

    </form>
</div>

<?php include("../body/footer.php"); ?>