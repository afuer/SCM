<?php
include('include.php');
$SearchId = getParam('search_id');

$RequisitionForMain = query("SELECT fd.REQUISITION_FOR,fd.ISPARTIAL
FROM gp_requesiton_details AS rd
LEFT JOIN gp_requisition_for_details AS fd On fd.REQUISITION_ID = rd.REQUISITION_ID
WHERE rd.REQUISITION_ID=$SearchId ");





$SqlRequisitionForContact = query("SELECT fc.CONTACT_NAME,fc.CONTACTNUMBEREMAIL
FROM gp_requesiton_details AS rd
LEFT JOIN gp_requisition_for_contact As fc on fc.REQUISITION_ID = rd.REQUISITION_ID
WHERE rd.REQUISITION_ID=$SearchId ");



$RequisitionForMain = find("SELECT fd.REQUISITION_FOR,fd.ISPARTIAL 
                        FROM gp_requisition_for_details As fd 
                        WHERE fd.REQUISITION_ID ='$SearchId' GROUP BY fd.REQUISITION_FOR");



include("../body/header.php");
?>


<form action="" method="POST" enctype="multipart/form-data" name='postform'>
    <fieldset class="fieldset">
        <legend>Selection Of BRANCH_NAME Or Division </legend>
        <table>
            <tr>
                <td width="200">Requisition For</td>

                <?php
                $rf = $RequisitionForMain->REQUISITION_FOR;
                if ($rf == '0') {
                    echo '<td> BRANCH_NAME</td><td></td>';
                }
                if ($rf == '1') {
                    echo '<td>Division</td><td></td>';
                }
                if ($rf == '0,1') {
                    echo '<td>BRANCH_NAME And Division</td>';
                }
                ?>

                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Selection</td>
                <?php
                $rs = $RequisitionForMain->ISPARTIAL;
                if ($rs == '0') {
                    echo '<td> Partial</td>';
                }
                if ($rs == '1') {
                    echo '<td>All</td>';
                }
                ?>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </fieldset>
    <br/>

    <?php if ($rs == '0') { ?>

        <div id="BRANCH_NAMEDivisionList">
            <?php
            if ($rf == '0' || $rf == '0,1') {
                $RequisitionForMainBRANCH_NAMEPartial = query("SELECT b.BRANCH_NAME
                FROM gp_requesiton_details AS rd
                LEFT JOIN gp_requisition_for_details AS fd On fd.REQUISITION_ID = rd.REQUISITION_ID
                INNER JOIN BRANCH_NAME AS b ON b.BRANCH_NAME_ID= fd.BRANCH_NAME_ID
                WHERE rd.REQUISITION_ID=$SearchId ");
                ?>

                <fieldset class="fieldset">
                    <table class="ui-state-default"  style="width:30%; float:left;" id="TableBRANCH_NAMEList">
                        <thead>
                        <th width="20">SL</th>
                        <th>BRANCH_NAME Name</th>
                        </thead>
                        <tbody>
                            <?php
                            $SL = 1;
                            while ($RowRequisitionForBRANCH_NAMEPartial = fetch_object($RequisitionForMainBRANCH_NAMEPartial)) {
                                ?>
                                <tr >
                                    <td ><?php echo $SL . '.'; ?></td>  
                                    </td>
                                    <td><?php echo $RowRequisitionForBRANCH_NAMEPartial->BRANCH_NAME; ?></td>
                                </tr>
                                <?php
                                $SL++;
                            }
                            ?>
                        </tbody>

                    </table>
                    <?php
                }
                if ($rf == '1' || $rf == '0,1') {
                    $SqlRequisitionForDivisionPartial = query("SELECT d.DIVISION
                    FROM gp_requesiton_details AS rd
                    LEFT JOIN gp_requisition_for_details AS fd On fd.REQUISITION_ID = rd.REQUISITION_ID
                    INNER JOIN division AS d ON d.DIVISIONID = fd.DIVISION_ID
                    WHERE rd.REQUISITION_ID=$SearchId ");
                    ?> 

                    <table class="ui-state-default"  style="width:30%; float:right;" id="TableDivisionList">

                        <thead>
                        <th width="20">SL</th>
                        <th >Division Name</th>

                        </thead>
                        <tbody>
                            <?php
                            $SL = 1;
                            while ($RowRequisitionForDivisionPartial = fetch_object($SqlRequisitionForDivisionPartial)) {
                                ?>
                                <tr >
                                    <td ><?php echo $SL . '.'; ?></td>  
                                    </td>
                                    <td><?php echo $RowRequisitionForDivisionPartial->DIVISION; ?></td>
                                </tr>
                                <?php
                                $SL++;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
                ?>
            </fieldset>  
        </div>

        <?php
    }
    if ($rs == '1') {

        $RequisitionForMainBRANCH_NAMEAll = query("SELECT b.BRANCH_NAME
        FROM gp_requesiton_details AS rd
        LEFT JOIN gp_requisition_for_details AS fd On fd.REQUISITION_ID = rd.REQUISITION_ID
        INNER JOIN BRANCH_NAME AS b ON b.BRANCH_NAME_ID= fd.BRANCH_NAME_ID
        WHERE rd.REQUISITION_ID=$SearchId ");

        $SqlRequisitionForDivisionAll = query("SELECT d.DIVISION
        FROM gp_requesiton_details AS rd
        LEFT JOIN gp_requisition_for_details AS fd On fd.REQUISITION_ID = rd.REQUISITION_ID
        INNER JOIN division AS d ON d.DIVISIONID = fd.DIVISION_ID
        WHERE rd.REQUISITION_ID=$SearchId ");

        if ($rf == '0' || $rf == '0,1') {
            ?>

            <fieldset> 
                <table class="ui-state-default"  style="width:47%; float:left;">

                    <thead>
                    <th width="20">SL</th>
                    <th >BRANCH_NAME Name</th>
                    </thead>
                    <tbody>
                        <?php
                        $SL = 1;
                        while ($RowRequisitionForBRANCH_NAMEAll = fetch_object($RequisitionForMainBRANCH_NAMEAll)) {
                            ?>
                            <tr>
                                <td><?php echo $SL . '.'; ?></td>
                                <td><?php echo $RowRequisitionForBRANCH_NAMEAll->BRANCH_NAME; ?></td>
                            </tr>
                            <?php
                            $SL++;
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            }
            if ($rf == '1' || $rf == '0,1') {
                ?>

                <table class="ui-state-default"  style="width:47%; float:right;">

                    <thead>
                    <th width="20">SL</th>

                    <th >Division Name</th>
                    </thead>
                    <tbody>
                        <?php
                        $SL = 1;
                        while ($RowRequisitionForDivisionAll = fetch_object($SqlRequisitionForDivisionAll)) {
                            ?>
                            <tr>
                                <td><?php echo $SL . '.'; ?></td>
                                <td><?php echo $RowRequisitionForDivisionAll->DIVISION; ?> </td>
                            </tr>
                            <?php
                            $SL++;
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
        </fieldset>      


        <?php
    }
    ?>






    <br/>
    <fieldset class="fieldset">
        <legend>Contact With</legend>
        <table class="ui-state-default" id="TableContactList">
            <thead>
            <th width="20" >SL</th>
            <th>Name</th>
            <th>Cell/Email</th>
            </thead>
            <tbody>
                <?php
                $SL = 1;
                while ($RowRequisitionForContact = fetch_object($SqlRequisitionForContact)) {
                    ?>
                    <tr>
                        <td><?php echo $SL . '.'; ?></td>
                        <td><?php echo $RowRequisitionForContact->CONTACT_NAME; ?></td>
                        <td><?php echo $RowRequisitionForContact->CONTACTNUMBEREMAIL; ?></td>

                    </tr>
                    <?php
                    $SL++;
                }
                ?>
            </tbody>

        </table>
    </fieldset>
    <button type="submit" name="save" value="SaveRequisitionFor" class="button" onClick="return verify()">Save</button>
</form>


<?php include("../body/footer.php"); ?>