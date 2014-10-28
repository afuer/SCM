
    <table class="table">
        <tr>
            <td width="120">Requisition No :  </td>
            <td width="200"> <?php  echo $var->REQUISITION_NO; ?> </td >
            <td width="120">Staff Member :</td>
            <td><?php echo $var->staff; ?></td>
        </tr>
        <tr >
            <td>Requisition Date :</td>
            <td><?php echo bdDate($var->REQ_DATE); ?> </td>
            <td>Location :</td>
            <td><?php echo user_location($userName); ?></td>
        </tr>
        <tr>
            <td>Created by :</td>
            <td><?php echo $var->CREATED_BY; ?></td>            
            <td>Priority:</td>
            <td><?php echo $var->PRIORITY_NAME; ?> </td
        </tr> 
        <tr>
            <td>Office Type:</td>
            <td><?php echo $var->OFFICE_NAME ?></td>
            <td>Branch/Dept : </td>            
            <td><?php echo $var->BRANCH_DEPT_NAME; ?></td>
        </tr> 
    </table>

