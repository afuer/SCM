<?php

if (isSave()) {
    if ($mode == 'new')
        $InsertMainSQL = "INSERT INTO cbl_budget ( CBL_BUDGET_ID, COSTCENTER_ID, GL_ACCOUNT_ID, BUDGET_YEAR, BUDGET_COMMENT, CREATED_BY, CREATED_DATE) VALUES
        ('$CblBudgetID', '$costcenter_id', '$ac_no', '$year_of', '$comments', '$user_name', now())";
    sql($InsertMainSQL);

    $InsertDetailsSQL = "INSERT INTO cbl_budget_details (CBL_BUDGET_DETAILS_ID, CBL_BUDGET_ID, JAN, FEB, MAR, APR, MAY, JUN,JUL, AUG, SEP,
            OCTO,NOV,DECE) VALUES ('$DetailsID','$CblBudgetID','$m1', '$m2', '$m3', '$m4', '$m5', '$m6', '$m7', '$m8', '$m9', '$m10', '$m11', '$m12' )";
    sql($InsertDetailsSQL);
}
?>
