<?php
include_once '../lib/DbManager.php';
include("../body/header.php");

$SearchId = getParam('cbl_budget_id');


$SelectSQL = "SELECT cb.CBL_BUDGET_ID,BUDGET_YEAR,COST_CENTER_NAME,GL_ACCOUNT_NAME,BUDGET_STATUS,BUDGET_YEAR,JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCTO,NOV,DECE,
    (JAN+FEB+MAR+APR+MAY+JUN+JUL+AUG+SEP+OCTO+NOV+DECE) AS TOTAL_YEAR_BUDGET
    FROM cbl_budget cb
    LEFT OUTER JOIN cbl_budget_details cbd ON cbd.CBL_BUDGET_ID= cb.CBL_BUDGET_ID
    LEFT OUTER JOIN cost_center cc ON  cc.COST_CENTER_ID=cb.COSTCENTER_ID
    LEFT OUTER JOIN gl_account ga ON ga.GL_ACCOUNT_ID=cb.GL_ACCOUNT_ID WHERE cb.CBL_BUDGET_ID='$SearchId'";
$ResultObj = find($SelectSQL);
?>
<div Title='Budget View' class="easyui-panel" style="height:1000px;" >
    <table class="table">
        <tr>
            <td width="200"><b>Cost Center : </b></td>
            <td><?php echo $ResultObj->COST_CENTER_NAME; ?></td>
        </tr>
        <tr>
            <td><b>Account Name :</b></td>
            <td><?php echo $ResultObj->GL_ACCOUNT_NAME; ?></td>
        </tr>
        <tr>
            <td><b>Year :</b></td>
            <td><?php echo $ResultObj->BUDGET_YEAR; ?></td>
        </tr>
    </table>

    <br/>
    <br/>

    <table class="table">
        <thead>
        <th align="center" colspan="4">MONTHS</th>
        </thead>
        <tr>
            <td width="150">January :</td>
            <td><?php echo $ResultObj->JAN; ?></td>
            <td width="150">February :</td>
            <td><?php echo $ResultObj->FEB; ?></td>
        </tr>
        <tr>
            <td>March :</td>
            <td><?php echo $ResultObj->MAR; ?></td>
            <td>April :</td>
            <td><?php echo $ResultObj->APR; ?></td>
        </tr>
        <tr>
            <td>May :</td>
            <td><?php echo $ResultObj->MAY; ?></td>
            <td> 	June :</td>
            <td><?php echo $ResultObj->JUN; ?></td>
        </tr>
        <tr>
            <td>July :</td>
            <td><?php echo $ResultObj->JUL; ?></td>
            <td>August ;</td>
            <td><?php echo $ResultObj->AUG; ?></td>
        </tr>
        <tr>
            <td>September :</td>
            <td><?php echo $ResultObj->SEP; ?></td>
            <td>October :</td>
            <td><?php echo $ResultObj->OCTO; ?></td>
        </tr>
        <tr>
            <td>November :</td>
            <td><?php echo $ResultObj->NOV; ?></td>
            <td>December :</td>
            <td><?php echo $ResultObj->DECE; ?></td>
        </tr>
    </table>

    <a href="CBLBudgetEdit.php?mode=edit&budget_id=<?php echo $ResultObj->CBL_BUDGET_ID; ?>" class="button">EDIT</a>
</div>