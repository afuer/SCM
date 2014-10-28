<?php
if ($mode != 'new' && $mode != 'search') {
    echo "$CreateLink";
    include_once ('pagination.php');

    $ActionLink = $ActionLink == '' ? $object_name : $ActionLink;

    //echo "SELECT MASTER_OBJECT_ID, SQL_QUERY FROM master_object WHERE TR_TYPE=2 AND object_name='$object_name'";
    //$grid_info = find("SELECT MASTER_OBJECT_ID, SQL_QUERY, GRID_TITLE FROM master_object WHERE TR_TYPE=2 AND object_name='$object_name'");


    $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    $limit = (int) (!isset($_GET["limit"]) ? 20 : $_GET["limit"]);
    $startpoint = ($page * $limit) - $limit;
    
    $sql= $grid_sql_query . " LIMIT $startpoint, $limit ";




    $query_result = query(strtolower($sql));

    $sql_explode = strtolower($sql);

    //for pagination
    $q = explode('from', $sql_explode);
    $sql_pa = "SELECT COUNT(*) as `num` from $q[1]";
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
    <br/>

    <div class="table-header"><?php echo $grid_title; ?></div> 
    <?php grid_top($total, $limit, $CreateLink); ?>
    <div class='table-worp' >
        <table id="resultTable" class="ui-state-default">
            <thead>
            <th width="20">SL</th>
            <?php
            $i = 1;
            while ($i < mysql_num_fields($query_result)) {
                $meta = mysql_fetch_field($query_result, $i);
                $head = str_replace("_", " ", $meta->name);
                echo "<th>$head</th>";
                $i++;
            }

            foreach ($details_link as $key => $value) {
                //$details = substr(ucwords(str_replace("_", " ", $value)), 0, -12);
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

                        echo "<td><a href='$value=$d[0]' target='_blank'>Add / Edit</a></td>";
                    }
                    echo "<td align='center' width='10'>";
                    viewIcon("$ActionLink[0].php?mode=search&search_id=$d[0]'");
                    echo "</td>";
                    echo "<td align='center' width='10'>";
                    editIcon("$ActionLink[1].php?mode=search&search_id=$d[0]'");
                    echo "</td>";
                    echo "<td align='center' width='10'>";
                    deleteIcon("$ActionLink[1].php?mode=delete&search_id=$d[0]");
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


