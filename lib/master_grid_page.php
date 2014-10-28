<?php
if ($mode != 'new' && $mode != 'search') {
    //include_once ('pagination.php');

    $ActionLink = $ActionLink == '' ? $object_name : $ActionLink;

    //echo "SELECT MASTER_OBJECT_ID, SQL_QUERY FROM master_object WHERE TR_TYPE=2 AND object_name='$object_name'";
    $grid_info = find("SELECT MASTER_OBJECT_ID, SQL_QUERY, GRID_TITLE FROM master_object WHERE TR_TYPE=2 AND object_name='$object_name'");


    $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
    $limit = (int) (!isset($_GET["limit"]) ? 20 : $_GET["limit"]);
    $startpoint = ($page * $limit) - $limit;

    //$sql= $grid_info->SQL_QUERY . " LIMIT $startpoint, $limit ";

    $query_result = query(strtolower($sql));

    $sql_explode = ucwords($sql);

    //for pagination
    $q = explode('FROM', $sql_explode);
    $sql_pa = "SELECT COUNT(*) as `num` FROM $q[1]";
    $q = explode('LIMIT', $sql_pa);
    $sql_pa = "$q[0]";
    $row = find($sql_pa);
    $total = $row->num;
    ?>


    <div class="table-header"><?php $grid_info->GRID_TITLE ?></div> 
    <?php //grid_top($total, $limit, $CreateLink, $object_name); ?>
    <div class="panel" style="width: 1231px;">
        <div class="panel-header accordion-header accordion-header-selected" style=" width: 1221px;">
            <div class="panel-body accordion-body panel-noscroll" title="" style="display: block; width: 1231px; height: 294px;">
                <div class="panel datagrid" style="width: 1231px;">
                    <div class="datagrid-wrap panel-body panel-body-noheader" title="" style="width: 1229px; height: 292px;">

                        <div class="datagrid-pager datagrid-pager-top pagination">
                            <table cellspacing="0" cellpadding="0" border="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="pagination-page-list">
                                                <option>10</option>
                                                <option>20</option>
                                                <option>30</option>
                                                <option>40</option>
                                                <option>50</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="pagination-btn-separator"></div>
                                        </td>
                                        <td>
                                            <a id="" class="l-btn l-btn-plain l-btn-disabled" href="javascript:void(0)">
                                                <span class="l-btn-left">
                                                    <span class="l-btn-text">
                                                        <span class="l-btn-empty pagination-first"> </span>
                                                    </span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a id="" class="l-btn l-btn-plain l-btn-disabled" href="javascript:void(0)">
                                                <span class="l-btn-left">
                                                    <span class="l-btn-text">
                                                        <span class="l-btn-empty pagination-prev"> </span>
                                                    </span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="pagination-btn-separator"></div>
                                        </td>
                                        <td>
                                            <span style="padding-left:6px;">Page</span>
                                        </td>
                                        <td>
                                            <input class="pagination-num" type="text" size="2" value="1">
                                        </td>
                                        <td>
                                            <span style="padding-right:6px;">of 1</span>
                                        </td>
                                        <td>
                                            <div class="pagination-btn-separator"></div>
                                        </td>
                                        <td>
                                            <a id="" class="l-btn l-btn-plain l-btn-disabled" href="javascript:void(0)">
                                                <span class="l-btn-left">
                                                    <span class="l-btn-text">
                                                        <span class="l-btn-empty pagination-next"> </span>
                                                    </span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <a id="" class="l-btn l-btn-plain l-btn-disabled" href="javascript:void(0)">
                                                <span class="l-btn-left">
                                                    <span class="l-btn-text">
                                                        <span class="l-btn-empty pagination-last"> </span>
                                                    </span>
                                                </span>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="pagination-btn-separator"></div>
                                        </td>
                                        <td>
                                            <a id="" class="l-btn l-btn-plain" href="javascript:void(0)">
                                                <span class="l-btn-left">
                                                    <span class="l-btn-text">
                                                        <span class="l-btn-empty pagination-load"> </span>
                                                    </span>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="pagination-info">Displaying 1 to 9 of 9 items</div>
                            <div style="clear:both;"></div>
                        </div>


                        <table class="easyui-datagrid" title="Basic DataGrid" style="width:700px;height:250px"  
                               data-options="singleSelect:true,collapsible:true,url:''">
                            <thead>
                                <tr>
                                    <th width="20">SL</th>
                                    <?php
                                    $i = 1;
                                    while ($i < mysql_num_fields($query_result)) {
                                        $meta = mysql_fetch_field($query_result, $i);
                                        $head = str_replace("_", " ", $meta->name);
                                        //echo "<th>$head</th>";
                                        echo "<th data-options=\"field:'" . $meta->name . "',width:" . 80 . "\">" . $head . "</th>";
                                        $i++;
                                    }

                                    foreach ($details_link as $key => $value) {
                                        //$details = substr(ucwords(str_replace("_", " ", $value)), 0, -12);
                                        //echo "<th> $key </th>";
                                        //echo "<th data-options=\"field:'" . $key. "',width:80>" . $key . "</th>";
                                    }
                                    ?>
                                    <th colspan="3">Action</th>
                                </tr>
                            </thead>
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
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    //pagination($total, $page, '?', $limit);
}
?>


