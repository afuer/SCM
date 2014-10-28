<table class="table" style="width: 800px;">
    <tr>
        <td width="120">PR No :  </td>
        <td width="200"><?php echo OrderNo($requisitionId); ?></td >
        <td width="120">Staff Member :</td>
        <td><?php echo $var->FIRST_NAME . ' ' . $var->LAST_NAME . ' (' . $userName . ')'; ?></td>
    </tr>
    <tr>
        <td>Requisition Date :</td>
        <td><?php echo bddate(date('Y-d-m')); ?></td>
        <td>Location :</td>
        <td><?php echo user_location($userName); ?></td>
    </tr>
    <tr>
        <td>Created by :</td>
        <td><?php echo $userName; ?></td>
        <td>Process Dept : </td>
        <td><?php echo $RquisitionType . '->' . $processDept ?></td>
    </tr>                    
</table>
