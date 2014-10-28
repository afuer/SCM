<?php

class RequisitionData {

    function AccountList() {
        return rs2array(query("SELECT GL_ACCOUNT_ID, CONCAT(GL_ACCOUNT_ID,'->',GL_ACCOUNT_NAME) AS ACC 
                FROM gl_account ORDER BY ACC"));
    }

    public function GetData($Requisition_Id, $REQUISITION_CC_ID) {
        $RequisitionData_SQL = "SELECT p.PRODUCT_CODE, p.PRODUCT_NAME, pcd.unite_price, pcd.quantity, (IFNULL(pcd.quantity,0)*IFNULL(pcd.unite_price,0)) AS price,
            CC_PERCENT, CC_AMOUNT, REQUISITION_DETAILS_ID, ut.UNIT_TYPE_NAME
        FROM requisition_cc_list rccl
        INNER JOIN product p ON p.PRODUCT_ID=rccl.PRODUCT_ID
        INNER JOIN requisition_details rd ON rd.PRODUCT_ID=rccl.PRODUCT_ID AND rd.REQUISITION_ID=rccl.REQUISITION_ID
        INNER JOIN price_comparison_details pcd ON pcd.productid=p.PRODUCT_ID AND pcd.productid=rd.PRODUCT_ID
        LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID
        WHERE rccl.REQUISITION_CC_ID='$REQUISITION_CC_ID' AND rccl.REQUISITION_ID='$Requisition_Id' AND selected=1";
        return query($RequisitionData_SQL);
    }

    function GetFromBudgeAllocation($Requisition_Id, $REQUISITION_CC_ID) {
        $sql = "SELECT rd.REQUISITION_DETAILS_ID, ba.BUDGET_ALLOCATION_ID, p.product_name, EXPENCE_ACCOUNT_ID, rd.QTY, 
            rd.UNIT_PRICE, ut.UNIT_TYPE_NAME, p.PRODUCT_GROUP_ID
            FROM budget_allocation AS ba
            INNER JOIN gp_requesiton_details AS rd ON rd.REQUISITION_ID = ba.REQUISITION_ID
            AND rd.REQUISITION_DETAILS_ID = ba.REQUISITION_DETAILS_ID
            LEFT JOIN product AS p ON p.product_id = rd.PRODUCT_ID
            LEFT JOIN unit_type AS ut ON ut.unit_type_id = p.UNIT_TYPE_ID
            WHERE ba.REQUISITION_ID='$Requisition_Id' AND ba.REQUISITION_CC_ID='$REQUISITION_CC_ID'";

        return query($sql);
    }

    function get_cc_list_by_requisition_id($Requisition_Id) {
       $sql = "SELECT cc.COST_CENTER_NAME, rcc.REQUISITION_CC_ID, PRODUCT_ID, 
            COST_CENTER_CODE, rcc.CC_PERCENT, CONCAT(s.SOL_CODE,' ',s.SOL_NAME) AS soleName
            FROM requisition_cc_list rcc
            INNER JOIN cost_center AS cc ON cc.cost_center_id = rcc.REQUISITION_CC_ID
            LEFT JOIN sol s ON s.SOL_ID=rcc.SOL_ID
            WHERE REQUISITION_ID='$Requisition_Id' GROUP BY REQUISITION_CC_ID";

        return query($sql);
    }

    function Save($Requisition_Id, $DetailId, $ExpenseAccount, $ProductGroupID, $user_name, $RequisitionCcId, $TotalPrice, $productId) {
        $sql = "INSERT INTO budget_allocation (REQUISITION_ID,REQUISITION_DETAILS_ID, EXPENCE_ACCOUNT_ID, PRODUCT_GROUP_ID, CREATED_BY, CREATED_DATE,REQUISITION_CC_ID,TOTAL, PRODUCT_ID) 
            VALUES('$Requisition_Id','$DetailId', '$ExpenseAccount', '$ProductGroupID', '$user_name', NOW(), '$RequisitionCcId','$TotalPrice', '$productId')";

        sql($sql);
    }

    function Update($ExpenseAccount, $ProductGroupID, $user_name, $budget_allocation_id) {
        $UpdateSQL = "UPDATE budget_allocation SET
         EXPENCE_ACCOUNT_ID='$ExpenseAccount', 
         PRODUCT_GROUP_ID='$ProductGroupID', 
         MODIFY_BY='$user_name', 
         CREATED_DATE=NOW() 
         WHERE BUDGET_ALLOCATION_ID=$budget_allocation_id";
        sql($UpdateSQL);
    }

    ////TTT
    public function GetDataBudgetConfig($Requisition_Id, $REQUISITION_CC_ID) {
        $BudgetConfig_SQL = "SELECT ba.BUDGET_ALLOCATION_ID, ea.GL_ACCOUNT_ID, ea.GL_ACCOUNT_NAME, 
        ba.TOTAL,ba.TAX,ba.VAT,ba.PAYABLE,pg.GROUP_NAME, tt.TAX_TYPE, ba.TAX_TYPE_ID,
        ea.GL_ACCOUNT_ID, ea.TAX AS 'GlTax', ea.VAT AS 'GlVat', 
        CONCAT(p.PRODUCT_CODE,'->',p.PRODUCT_NAME) AS productName, ba.VAT, ba.TAX
        
        FROM budget_allocation As ba
        INNER JOIN gl_account AS ea ON ea.GL_ACCOUNT_ID = ba.EXPENCE_ACCOUNT_ID
        LEFT JOIN requisition_details rd ON rd.REQUISITION_DETAILS_ID=ba.REQUISITION_DETAILS_ID
        LEFT JOIN product p ON p.PRODUCT_ID=rd.PRODUCT_ID
        LEFT JOIN product_group AS pg ON pg.PRODUCT_GROUP_ID = ea.GL_TYPE_ID
        LEFT JOIN tax_type tt ON tt.TAX_TYPE_ID=ba.TAX_TYPE_ID
        WHERE ba.REQUISITION_ID = '$Requisition_Id' AND ba.REQUISITION_CC_ID='$REQUISITION_CC_ID'";
        return query($BudgetConfig_SQL);
    }

    function UpdateBudgetConfig($AllocationID, $TaxType, $Tax, $Vat, $Payable) {
        $UpdateSQL = "UPDATE budget_allocation SET
         TAX_TYPE_ID='$TaxType', 
         TAX='$Tax', 
         VAT='$Vat', 
         PAYABLE='$Payable'
         WHERE BUDGET_ALLOCATION_ID='$AllocationID'";
        sql($UpdateSQL);
    }

    function get_gl_list_by_requisition_id($Requisition_Id) {
        $sql = "SELECT ba.REQUISITION_ID,ea.GL_ACCOUNT_NAME,ea.GL_ACCOUNT_CODE,
        ba.EXPENCE_ACCOUNT_ID, rm.REQUISITION_NO, ea.GL_TYPE_ID
            FROM budget_allocation AS ba 
            LEFT JOIN gp_requesiton_details As grd  ON grd.REQUISITION_DETAILS_ID = ba.REQUISITION_DETAILS_ID
            LEFT JOIN gl_account As ea ON ea.GL_ACCOUNT_ID = ba.EXPENCE_ACCOUNT_ID
            LEFT JOIN gp_requesition AS rm ON rm.REQUISITION_ID=ba.REQUISITION_ID
            WHERE ba.REQUISITION_ID = '$Requisition_Id' GROUP BY ea.GL_TYPE_ID";

        return query($sql);
    }

    public function GetDataGL($Requisition_Id, $GL_TYPE_ID) {
        $RequisitionData_SQL = "SELECT ba.REQUISITION_ID, ea.GL_ACCOUNT_NAME, ea.GL_ACCOUNT_CODE, p.PRODUCT_NAME,
        SUM(grd.QTY) AS 'QTY', u.UNIT_TYPE_NAME,
        SUM(grd.QTY*grd.UNIT_PRICE) As total, grd.UNIT_PRICE

        FROM budget_allocation AS ba 
        LEFT JOIN gp_requesiton_details As grd  ON grd.REQUISITION_DETAILS_ID = ba.REQUISITION_DETAILS_ID
        LEFT JOIN gl_account As ea ON ea.GL_ACCOUNT_ID = ba.EXPENCE_ACCOUNT_ID
        LEFT JOIN product AS p ON p.product_id = grd.PRODUCT_ID
        LEFT JOIN unit_type AS u ON u.UNIT_TYPE_ID=p.UNIT_TYPE_ID
        WHERE ba.REQUISITION_ID = '$Requisition_Id' AND  ea.GL_TYPE_ID='$GL_TYPE_ID'
        GROUP BY ea.GL_ACCOUNT_ID";
        return query($RequisitionData_SQL);
    }

    function ResultRequisitionMain($Requisition_Id) {
        $sql = "SELECT rm.REQUISITION_NO, rm.REQUISITION_DATE, rm.CREATED_BY,
        e.CARD_NO, amt.AMOUNT_TYPE_ID, rm.BUDGET_ALLOCATED, et.EXPENSE_TYPE_ID, et.EXPENSE_TYPE_NAME, 
        wt.WORKFLOW_TYPE_NAME, rm.APPROVE_FILE_TYPE, rm.APPROVE_FILE_PATH,
        rm.SPECIFICATION, rm.JUSTIFICATION, rm.REMARK, e.FULL_NAME,
        AMOUNT_TYPE_NAME, workflow_name,rr.PRIORITY_NAME,rm.PRIORITY_ID

        FROM requisition AS rm
        LEFT JOIN expense_type AS et ON et.EXPENSE_TYPE_ID=rm.EXPENSE_TYPE_ID
        LEFT JOIN workflow_type AS wt ON wt.WORKFLOW_TYPE_ID=rm.WORKFLOW_TYPE_ID
        LEFT JOIN amount_type AS amt ON amt.AMOUNT_TYPE_ID=rm.AMOUNT_TYPE_ID 
        LEFT JOIN employee AS e ON e.EMPLOYEE_ID=rm.CREATED_BY
        LEFT JOIN workflow_group AS wg ON wg.workflow_group_id=rm.WORKFLOW_GROUP_ID
        LEFT JOIN priority AS rr ON rr.PRIORITY_ID = rm.PRIORITY_ID
        WHERE rm.REQUISITION_ID ='$Requisition_Id'";
        return find($sql);
    }

}

?>
