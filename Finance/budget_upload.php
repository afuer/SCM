<?php
include '../lib/DbManager.php';
include '../body/header.php';

if (isSave()) {

    $path = file_upload_single('../documents/budget/');
    //echo "<script>location.replace('budget_upload.php');</script>";
}

//$path = getParam('search_id');

set_time_limit(0);

date_default_timezone_set('Europe/London');
?>

<div class="panel-header">New Budget Entry</div>
<div style="background: white; padding: 5px 5px;">
    <form action="" method="POST" class="formValidate" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="save" value="new"/>
        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Attachment Title</legend>

            <table class="ui-state-default" id="attachment_tab" style="width: 780px;">
                <thead>
                <th width="20">SL.</th>
                <th>Attach File</th>
                </thead>
                <tbody>
                    <tr>
                        <td>1.</td>
                        <td><input type='file' name='file_one' class="required"/></td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="button">Upload File</button>
        </fieldset>
    </form>



    <?php
    /** Include path * */
    //set_include_path(get_include_path() . PATH_SEPARATOR . '../public/PHPOffice-PHPExcel/Classes/');

    /** PHPExcel_IOFactory */
    //include 'PHPExcel/IOFactory.php';
//$inputFileName = './sampleData/example1.xls';
    //echo 'Loading file ', pathinfo($inputFileName, PATHINFO_BASENAME), ' using IOFactory to identify the format<br />';
    //$objPHPExcel = PHPExcel_IOFactory::load($path);
    //echo '<hr />';
    //$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
    //print_r($sheetData);
    ?>

    <div id="show_excel" >
        <?php
        if ($_FILES['file_one']['name'] != '') {

            require_once 'reader/Classes/PHPExcel/IOFactory.php';

            //Funciones extras

            function get_cell($cell, $objPHPExcel) {
                //select one cell
                $objCell = ($objPHPExcel->getActiveSheet()->getCell($cell));
                //get cell value
                return $objCell->getvalue();
            }

            function pp(&$var) {
                $var = chr(ord($var) + 1);
                return true;
            }

            $type = $_FILES['file_one']['type'];

            if ($type == 'application/vnd.ms-excel') {
                // Extension excel 97
                $ext = 'xls';
            } else if ($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                // Extension excel 2007 y 2010
                $ext = 'xlsx';
            } else {
                // Extension no valida
                echo -1;
                exit();
            }

            $xlsx = 'Excel2007';
            $xls = 'Excel5';

            //creando el lector
            $objReader = PHPExcel_IOFactory::createReader($$ext);

            //cargamos el archivo
            $objPHPExcel = $objReader->load($path);

            $dim = $objPHPExcel->getActiveSheet()->calculateWorksheetDimension();

            // list coloca en array $start y $end
            list($start, $end) = explode(':', $dim);

            if (!preg_match('#([A-Z]+)([0-9]+)#', $start, $rslt)) {
                return false;
            }
            list($start, $start_h, $start_v) = $rslt;
            if (!preg_match('#([A-Z]+)([0-9]+)#', $end, $rslt)) {
                return false;
            }
            list($end, $end_h, $end_v) = $rslt;

            //empieza  lectura vertical
            //$table = "<table class='table'>";
            for ($v = $start_v; $v <= $end_v; $v++) {
                //empieza lectura horizontal
                //if ($v > 5) {
//                $table .= "<tr>";
//                for ($h = $start_h; ord($h) <= ord($end_h); pp($h)) {
//                    $cellValue = get_cell($h . $v, $objPHPExcel);
//                    //echo get_cell($h . $v, $objPHPExcel);
//                    $table .= "<td>";
//                    if ($cellValue !== null) {
//                        $table .= $cellValue;
//                    }
//                    $table .= "</td>";
//                }

                $GL_ID = get_cell('B' . $v, $objPHPExcel);
                $GL_Name = get_cell('C' . $v, $objPHPExcel);
                $JAN = get_cell('E' . $v, $objPHPExcel);
                $FEB = get_cell('F' . $v, $objPHPExcel);
                $MAR = get_cell('G' . $v, $objPHPExcel);
                $APR = get_cell('H' . $v, $objPHPExcel);
                $MAY = get_cell('I' . $v, $objPHPExcel);
                $JUN = get_cell('J' . $v, $objPHPExcel);
                $JUL = get_cell('K' . $v, $objPHPExcel);
                $AUG = get_cell('L' . $v, $objPHPExcel);
                $SEP = get_cell('M' . $v, $objPHPExcel);
                $OCT = get_cell('N' . $v, $objPHPExcel);
                $NOV = get_cell('O' . $v, $objPHPExcel);
                $DEC = get_cell('P' . $v, $objPHPExcel);
                $TOTAL_AMOUNT = get_cell('Q' . $v, $objPHPExcel);

                if ($v > 5) {
                    $sql = "INSERT INTO budget(GL_ID, JAN, FEB, MAR, APR, MAY, JUN, JUL, AUG, SEP, OCT, NOV, `DEC`, TOTAL_AMOUNT, CREATED_BY, CREATED_DATE) 
                                VALUES('$GL_ID','$JAN','$FEB','$MAR','$APR','$MAY','$JUN','$JUL','$AUG','$SEP','$OCT','$NOV','$DEC', '$TOTAL_AMOUNT', '$employeeId', NOW())";
                    sql($sql);

//                    $glUpdate="UPDATE gl_account SET GL_ACCOUNT_ID='', 
//                        GL_ACCOUNT_NAME='', 
//                        GL_STATUS='-1', CREATED_BY='', 
//                        CREATED_DATE=NOW()";
                    $count = findValue("SELECT COUNT(*) FROM gl_account WHERE GL_ACCOUNT_ID='$GL_ID'");
                    if ($count == 0) {

                        $glInsert = "INSERT INTO gl_account(GL_ACCOUNT_ID, GL_ACCOUNT_NAME, GL_STATUS, CREATED_BY, CREATED_DATE)
                        VALUES('$GL_ID', '$GL_Name', '-1', '$employeeId', NOW())";
                        sql($glInsert);
                    }

                    //echo "<br/>";
                }
                //$table .= "</tr>";
            }
            //}
            //$table .= "</table>";
            //echo $table;
            echo "<script>location.replace('budget_upload.php');</script>";
        }

        $result = query("SELECT GL_ID, gl.GL_ACCOUNT_NAME, JAN, FEB, MAR, APR, MAY, JUN, JUL, AUG, SEP, OCT, NOV, `DEC`, TOTAL_AMOUNT 
        FROM budget b
        INNER JOIN gl_account gl ON gl.GL_ACCOUNT_ID=b.GL_ID ");
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.ui-state-default td').css('text-align', 'right');
            });
        </script>
        <table class="ui-state-default">
            <thead>
            <th>GL</th>
            <th>GL Name</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>May</th>
            <th>Jun</th>
            <th>Jul</th>
            <th>Aug</th>
            <th>Sep</th>
            <th>Oct</th>
            <th>Nov</th>
            <th>Dec</th>
            <th>Total</th>
            </thead>
            <tbody>
                <?php while ($row = mysql_fetch_object($result)) { ?>

                    <tr>
                        <td><?php echo $row->GL_ID; ?></td>
                        <td><?php echo $row->GL_ACCOUNT_NAME; ?></td>
                        <td><?php echo $row->JAN; ?></td>
                        <td><?php echo $row->FEB; ?></td>
                        <td><?php echo $row->MAR; ?></td>
                        <td><?php echo $row->APR; ?></td>
                        <td><?php echo $row->MAY; ?></td>
                        <td><?php echo $row->JUN; ?></td>
                        <td><?php echo $row->JUL; ?></td>
                        <td><?php echo $row->AUG; ?></td>
                        <td><?php echo $row->SEP; ?></td>
                        <td><?php echo $row->AUG; ?></td>
                        <td><?php echo $row->NOV; ?></td>
                        <td><?php echo $row->DEC; ?></td>
                        <td><?php echo $row->TOTAL_AMOUNT; ?></td>
                    </tr>
                    <?php
                }
                ?>

            </tbody>
        </table>









    </div>
</div>




<?php include '../body/footer.php'; ?>