<?php
include './DbManager.php';;
include 'pagination.php';


$search_id = getParam('search_id');
$mode = getParam('mode');
$object_id = getParam('object_id');

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = (int) (!isset($_GET["limit"]) ? 250 : $_GET["limit"]);
$startpoint = ($page * $limit) - $limit;

$where = ($object_id == '') ? '' : " WHERE object_id='$object_id'";

$selectSQL = "select * FROM master_object $where LIMIT $startpoint, $limit";

$rec = $db->query($selectSQL);

//for pagination
$q = explode('FROM', $selectSQL);
$sql_pa = "SELECT COUNT(*) as `num` FROM $q[1]";
$q = explode('LIMIT', $sql_pa);
$sql_pa = "$q[0]";
$row = find($sql_pa);
$total = $row->num;



if (isSave()) {
    $object_name = getParam('object_name');
    $page_title = getParam('page_title');
    $grid_title = getParam('grid_title');
    $view_column = getParam('view_column');
    $grid_table = getParam('grid_table');
    $grid_table_where_cond = getParam('grid_table_where_cond');
    $show_per_page = getParam('show_per_page');
    $_data_sort_column_no = getParam('_data_sort_column_no');
    $_data_sort_by = getParam('_data_sort_by');
    $instructions = getParam('instructions');


    if ($mode == 'new') {
        $sql = "insert into master_object (object_name, page_title, grid_title, view_column, grid_table, grid_table_where_cond, show_per_page, _data_sort_column_no , _data_sort_by, instructions) 
			values ('$object_name', '$page_title',  '$grid_title','$view_column', '$grid_table', '$grid_table_where_cond', '$show_per_page', '$_data_sort_column_no','$_data_sort_by','$instructions') ";
        sql($sql);

        $object_name_id = $object_name . '_id';
        $sql_create = "CREATE TABLE IF NOT EXISTS $object_name (
      `$object_name_id` int(11) NOT NULL AUTO_INCREMENT,
      `created_by` varchar(50) NOT NULL,
      `created_date` datetime NOT NULL,
      `modify_by` varchar(50) NULL,
      `modify_date` date  NULL,
      PRIMARY KEY ($object_name_id)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";


        sql($sql_create);
    } else {
        $updateSQL = "update master_object set
        object_name='$object_name', 
        page_title='$page_title', 
        grid_title='$grid_title', 
        view_column='$view_column', 
        grid_table='$grid_table', 
        grid_table_where_cond='$grid_table_where_cond', 
        show_per_page='$show_per_page', 
        _data_sort_column_no='$_data_sort_column_no', 
        _data_sort_by='$_data_sort_by', 
        instructions='$instructions' 
        where object_id =$search_id";

        sql($updateSQL);
    }
    echo "<script>location.replace('create_object.php?mode=');</script>";
}


if ($mode == 'delete') {
    $sql1 = "delete from master_object where object_id =$search_id";

    if (sql($sql1)) {
        echo "<script>location.replace('create_object.php?mode=');</script>";
    }
}


if ($search_id) {
    $sql2 = "select object_name, page_title, grid_title, view_column, grid_table, grid_table_where_cond, show_per_page, _data_sort_column_no , _data_sort_by, instructions
	from master_object where object_id='$search_id'";

    $var = find($sql2);
}

include '../body/master_header.php';
if ($mode != 'new' || $mode == 'search') {
    ?> 
    <a href="create_object.php?mode=new" class="create button">Create New</a>
    <a href="create_object.php"><buttin class="button">Refresh</buttin></a>
    <?php
}
if ($mode == 'new' || $mode == 'search') {
    ?>
    <fieldset>
        <legend><strong>Master Object Creator</strong></legend>
        <form action='create_object.php' method='POST'>
            <table>
                <tr>
                    <td width="120">Object Name:</td>
                    <td><?php textbox("object_name", $var->object_name, 20); ?> </td>
                </tr>
                <tr>
                    <td>Page Title:</td>
                    <td><input type="text" name="page_title" value="<?php echo $var->page_title; ?>" size="20"/></td>
                </tr>

                <tr>
                    <td>View Column:</td>
                    <td><input type="text" name="view_column" value="<?php echo $var->view_column; ?>" size="20"/></td>
                </tr>

                <tr>
                    <td>Show Per Page:</td>
                    <td><input type="text" name="show_per_page" value="<?php echo $var->show_per_page; ?>" size="20"/></td>
                </tr>
                <tr>
                    <td>Data Sort Column No:</td>
                    <td><input type="text" name="_data_sort_column_no" value="<?php echo $var->_data_sort_column_no; ?>" size="20"/></td>
                </tr>
                <tr>
                    <td>Data Sort By:</td>
                    <td><input type="text" name="_data_sort_by" value="<?php echo $var->_data_sort_by; ?>" size="20"/></td>
                </tr>
                <tr>
                    <td>Instructions:</td>
                    <td><input type="text" name="instructions" value="<?php echo $var->instructions; ?>" size="20"/></td>
                </tr>
            </table>
            <br/>
            <?php saveButton() ?>
            <input type="hidden" name="mode" value="<?php echo $mode ?>" />
            <input type="hidden" name="search_id" value="<?php echo $search_id ?>" />
        </form>
    </fieldset>
<?php } else { ?>
    <br/><br/>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#excel').click(function(){
                var data=$('#resultTable').table2CSV();
                
                $('#data').val(data);
                //window.location.href = '../lib/xl_print.php?data='+data;
            });
            
            $(".ui-state-default").delegate("tr", "click", function() {
                $(this).addClass("even DTTT_selected").siblings().removeClass("even DTTT_selected");
            });
            
            $("table.ui-state-default").tablesorter();
            //$("table.ui-state-default").tableFilter();

        });
    </script>

    <h3>Master Object Creator</h3> 
    <?php table_top($total, $limit); ?>
    <table id="resultTable" class="ui-state-default">
        <thead>
        <th width='20'>SI</th>
        <th width='200'>Object Name</th>
        <th width='250'>Page Title</th>
        <th width='20'>View Column</th>
        <th width='20'>Show Per Page</th>
        <th width='20'>Sort Column</th>
        <th width='20'>Sort</th>
        <th>Instructions</th>
        <th width='20'>Action</th>
    </thead>
    <?php
    $sl = 1;

    while ($row = fetch_object($rec)) {

        echo "<tr>";
        echo "<td>$sl</td>";
        echo "<td>$row->object_name</td>";
        echo "<td>$row->page_title</td>";
        echo "<td align='center'>$row->view_column</td>";
        echo "<td align='center'>$row->show_per_page</td>";
        echo "<td align='center'>$row->_data_sort_column_no</td>";
        echo "<td align='center'>$row->_data_sort_by</td>";
        echo "<td>$row->instructions</a></td>";
        echo "<td align='center'>";
        editIcon("create_object.php?mode=search&search_id=$row->object_id");
        deleteIcon("create_object.php?mode=delete&search_id=$row->object_id");
        echo"</td>";
        echo "</tr>";
        $sl++;
    }
    ?> 
    </table>
    <?php
    pagination($total, $page, '?', $limit);
}
include("../body/footer.php");
?>
