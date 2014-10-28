<?php
include_once ('pagination.php');

$object_id = findValue("SELECT object_id from master_object where object_name='$object_name'");
$grid_info = find("SELECT * from master_grid where object_id='$object_id'");
$selectSQL = "SELECT show_page, body_instruction FROM master_grid WHERE object_id='$object_id'";
$grid_table_name = "$grid_info->grid_title";
$header_row = find($selectSQL);

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = (int) (!isset($_GET["limit"]) ? 20 : $_GET["limit"]);
$startpoint = ($page * $limit) - $limit;


//must be check
if (isset($main_id)) {
    $res = "='$main_id'";
}

$sql = "$header_row->body_instruction" . $res . " LIMIT $startpoint, $limit ";
$query_result = mysql_query($sql);

$sql_explode = "$header_row->body_instruction $res ";

//for pagination
$q = explode('FROM', $sql_explode);
$sql_pa = "SELECT COUNT(*) as `num` FROM $q[1]";
$q = explode('LIMIT', $sql_pa);
$sql_pa = "$q[0]";
$row = find($sql_pa);
$total = $row->num;
?>
<script type="text/javascript">
    $(document).ready(function(){
        
        $('#excel').click(function(){
            var data=$('#resultTable').table2CSV();
            window.location.href = '../lib/xl_print.php?data='+data;
        });
        
        $(".ui-state-default").delegate("tr", "click", function() {
            $(this).addClass("even DTTT_selected").siblings().removeClass("even DTTT_selected");
        });
        
        $("table.ui-state-default").tablesorter();
        //$("table.ui-state-default").tableFilter();
    });
</script>
<?php if ($mode != 'new' && $mode != 'search') { ?>

    <?php table_top($total, $limit); ?>
    <div class='table-worp' >
        <table id="resultTable" class="ui-state-default">
            <thead>
            <th>SI</th>
            <?php
            $i = 1;
            while ($i < mysql_num_fields($query_result)) {
                $meta = mysql_fetch_field($query_result, $i);
                $head = ucwords(str_replace("_", " ", $meta->name));
                echo "<th>$head</th>";
                $i++;
            }

            foreach ($details_link as $key => $value) {
                //$details = ucwords(str_replace("_", " ", $value));
                echo "<th> $key </th>";
            }
            ?>
            <th colspan="3">Action</th>
            </thead>

            <tbody>
                <?php
                $count_field = mysql_num_fields($query_result);
                $sl = $page * $limit + 1 - $limit;
                while ($d = mysql_fetch_row($query_result)) {
                    echo "<tr>";
                    echo "<td width='20'>$sl</td>";
                    for ($j = 1; $j < $count_field; $j++) {
                        echo "<td>";
                        if ($d[$j] == 'image') {
                            echo "<a class='fancybox' href='files/$d[$j]' data-fancybox-group='gallery' title='$d[$j]'><img src='files/$d[$j]' alt='' width='50' height='40' /></a>";
                            //echo "<img src='files/$d[$j]' width='30', height='20'/>";
                        } else {
                            echo $d[$j];
                        }
                        echo "</td>";
                    }

                    foreach ($details_link as $key => $value) {
                        echo "<td><a href='$value=$d[0]' target='_blank'>Details</a></td>";
                    }
                    echo "<td align='center'>";
                    viewIcon("$object_name.php?main_id=$main_id&mode=search&search_id=$d[0]'");
                    echo "</td>";
                    echo "<td align='center'>";
                    editIcon("$object_name.php?main_id=$main_id&mode=search&search_id=$d[0]'");
                    echo "</td>";
                    echo "<td align='center'>";
                    deleteIcon("$object_name.php?main_id=$main_id&mode=delete&search_id=$d[0]");
                    echo "</td>";
                    echo "</tr>";
                    $sl++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    pagination($total, $page, '?', $limit);
}
?>


