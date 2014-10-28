<?php     
include '../lib/DbManager.php';

$val = getParam("val");
$supplierid = getParam("supplierid");

$is_found = findValue("SELECT det.invoice_no 
FROM fin_payment_approval_note det
WHERE INVOICE_NO='$val' AND BENEFICIARY_ID='$supplierid'");  
                  
                              
    if(!empty($is_found))
      {
            echo "The Invoice <font color='#FF0000' size='7'><b> $val </b></font>is already Exist";//<input type='hidden' name='is_duplicate' value='1' />"; 
    }
    else{
           echo "This is a new Invoice";//<input type='hidden' name='is_duplicate' value='0' />";  
    } 
?>