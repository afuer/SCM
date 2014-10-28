<?php
include_once 'include.php';
//include_once 'JSON.php';


if (isset($_GET['data'])) {
    $ObjManageBudget = json_decode($_GET['data']);
    
    $amount_value=$ObjManageBudget->amount_value;
    $designation=$ObjManageBudget->designation;
    $gl_account=$ObjManageBudget->gl_account;
    $year=$ObjManageBudget->year;
    $CapexOpexType=$ObjManageBudget->capex_opex_type;   // combo BOX
    $ProcType=$ObjManageBudget->proc_type; // expense type id
            
    
    $SelectSQL="SELECT da.amount
    FROM delegation_authority da
    WHERE da.gl_account_id='$gl_account' AND da.designation_id='$designation' AND da.year='$year' 
    AND da.op_cap_type='$CapexOpexType' AND da.EXPENSE_TYPE_ID='$ProcType'";
    
    $IsNew=findValue($SelectSQL);
    
    if($IsNew=='')
    {
        $PK=  NextId("delegation_authority", "delegation_authority_id");
        $InsertSQLDetails="INSERT INTO delegation_authority (delegation_authority_id, gl_account_id, designation_id, amount, created_by, created_date, year, op_cap_type, expense_type_id) 
        VALUES ('$PK','$gl_account','$designation','$amount_value','$user_name',now(),'$year','$CapexOpexType','$ProcType')";
        query($InsertSQLDetails);
    }
    else
    {
       $UpdateSQL="UPDATE delegation_authority SET
                   amount='$amount_value',
                   modify_by='$user_name',
                   modify_date=now()
                   WHERE gl_account_id='$gl_account' AND designation_id='$designation' AND year='$year' AND op_cap_type='$CapexOpexType' AND EXPENSE_TYPE_ID='$ProcType'";
       query($UpdateSQL);
    }
    
    
    //print_r($ObjManageBudget);
}
else {
    echo 'not GET';
}
?>
