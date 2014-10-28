<?php

function table_top($total, $limit) {
    $display_record_list = array(array('10', '10'), array('25', '25'), array('50', '50'), array('100', '100'), array('500', '500'), array('1000', '1000'));
    ?>
    <div style="height: 35px" class="fc fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <div class="dataTables_length" id="dt_ulis_length">
            <label>
                Display <?php comboBox_table_top('limit', $display_record_list, $limit, FALSE); ?>
                records
            </label>

        </div>
        <div class="dataTables_filter"><label>Search: <input class="quickfind" id="filter" type="text"/></label></div>
        <div style="padding-top: 10px;" class="float-right">Total Found: <strong><?php echo $total; ?></strong></div>
        <form action="../lib/xl_print.php" method="POST">
            <input type="hidden" name="data" id="data" value=""/>
            <button type="submit" class="ui-widget-header" id="excel">Export Excel</button> 

        </form>

    </div>
    <?php
}

//grid top
function grid_top($total, $limit, $CreateLink = null, $object_name = NULL) {

    $display_record_list = array(array('10', '10'), array('25', '25'), array('50', '50'), array('100', '100'), array('500', '500'), array('1000', '1000'));
    $CreateLink = $CreateLink == null ? '' : $CreateLink;
    ?>
    <div style="height: 35px" class="fc fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">
        <div class="dataTables_length" id="dt_ulis_length">
            <label>
                Display <?php comboBox_table_top('limit', $display_record_list, $limit, FALSE); ?>
                records
            </label>

        </div>
        <div class="dataTables_filter"><label>Search: <input class="quickfind" id="filter" type="text"/></label></div>
        <div>
            <a href="<?php echo $CreateLink; ?>" class="button create" title="<?php echo $object_name.'New'?>" >Create New</a>
            Total Found: <strong><?php echo $total; ?></strong>
        </div>
        <form action="../lib/xl_print.php" method="POST">
            <input type="hidden" name="data" id="data" value=""/>
            <!--<button type="submit" class="ui-widget-header" id="excel">Export Excel</button> -->

        </form>

    </div>
    <?php
}

/**
 * @link: http://www.Awcore.com/dev
 */
function pagination($total, $page, $url = '?', $limit) {

    $adjacents = "2";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $limit;

    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $limit);
    $lpm1 = $lastpage - 1;
    ?>


    <div style="height: 35px" class="fc fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix">
        <div id="tables_info" class="dataTables_info">Showing <?php echo $page . ' to ' . $lastpage . ' of ' . $total . ' entries'; ?></div>
        <div id="tables_paginate" class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers">
            <span id="tables_first" class="first ui-corner-tl ui-corner-bl fg-button ui-button ui-state-default">First</span>
            <span>
                <?php
                if ($lastpage > 1) {
                    if ($lastpage < 7 + ($adjacents * 2)) {
                        for ($counter = 1; $counter <= $lastpage; $counter++) {
                            if ($counter == $page)
                                echo "<span class='fg-button ui-button ui-state-default ui-state-disabled'><a>$counter</a></span>";
                            else
                                echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=$counter&limit=$limit'>$counter</a></span>";
                        }
                    }
                    elseif ($lastpage > 5 + ($adjacents * 2)) {
                        if ($page < 1 + ($adjacents * 2)) {
                            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                                if ($counter == $page)
                                    echo "<span class='fg-button ui-button ui-state-default ui-state-disabled'><a>$counter</a></span>";
                                else
                                    echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=$counter&limit=$limit'>$counter</a></span>";
                            }
                            echo "<span class='fg-button ui-button ui-state-default'>...</span>";
                            echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=$lpm1&limit=$limit'>$lpm1</a></span>";
                            echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=$lastpage&limit=$limit'>$lastpage</a></span>";
                        }
                        elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                            echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=1&limit=$limit'>1</a></span>";
                            echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=2&limit=$limit'>2</a></span>";
                            echo "<span class='fg-button ui-button ui-state-default'>...</span>";

                            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                                if ($counter == $page)
                                    echo "<span class='fg-button ui-button ui-state-default ui-state-disabled'>$counter</span>";

                                else
                                    echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=$counter&limit=$limit'>$counter</a></span>";
                            }
                            echo "<span class='fg-button ui-button ui-state-default'>...</span>";
                            echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=$lpm1&limit=$limit'>$lpm1</a></span>";
                            echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=$lastpage&limit=$limit'>$lastpage</a></span>";
                        }
                        else {
                            echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=1&limit=$limit'>1</a></span>";
                            echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=2&limit=$limit'>2</a></span>";
                            echo "<span class='fg-button ui-button ui-state-default'>...</span>";

                            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                                if ($counter == $page)
                                    echo "<span class='fg-button ui-button ui-state-default ui-state-disabled'>$counter</span>";
                                else
                                    echo "<span class='fg-button ui-button ui-state-default'><a href='{$url}page=$counter&limit=$limit'>$counter</a></span>";
                            }
                        }
                    }
                }
                ?>
            </span>
            <span id="tables_first" class="last ui-corner-tr ui-corner-br fg-button ui-button ui-state-default">Last</span>
        </div>
    </div>
    <?php
}
?>