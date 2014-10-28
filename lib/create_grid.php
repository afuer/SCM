<?php
include './DbManager.php';
include_once ('pagination.php');

$search_id = getParam('search_id');
$mode = getParam('mode');

$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
$limit = (int) (!isset($_GET["limit"]) ? 50 : $_GET["limit"]);
$startpoint = ($page * $limit) - $limit;


if (isSave()) {
    $object_id = getParam('object_id');
    $grid_title = getParam('grid_title');
    $show_page = getParam('show_page');
    $SQL_QUERY = getParam('SQL_QUERY');


    if ($mode == 'new') {
        echo $sql = "insert into MASTER_OBJECT (OBJECT_NAME, SQL_QUERY, TR_TYPE, GRID_TITLE) 
			values ('$object_id', '$SQL_QUERY', 2, '$grid_title') ";
        sql($sql);
    } else {
        $updateSQL = "UPDATE MASTER_OBJECT SET
		OBJECT_NAME='$object_id',  
		SQL_QUERY='$SQL_QUERY',
                GRID_TITLE='$grid_title'
		WHERE MASTER_OBJECT_ID ='$search_id' AND TR_TYPE='2'";

        sql($updateSQL);

        
    }
    echo "<script>location.replace('create_grid.php?mode=');</script>";
    //die();
}


if ($mode == 'delete') {
    $sql1 = "delete from modules where object_id =$search_id";

    if (sql($sql1)) {
        echo "<script>location.replace('create_grid.php?mode=');</script>";
    }
}


if (($search_id)) {
    $sql2 = "SELECT MASTER_OBJECT_ID, OBJECT_NAME, SQL_QUERY, GRID_TITLE FROM MASTER_OBJECT WHERE MASTER_OBJECT_ID='$search_id'";

    $var = find($sql2);
    $MASTER_OBJECT_ID = $var->MASTER_OBJECT_ID;
    $GRID_TITLE = $var->GRID_TITLE;
    $SQL_QUERY = $var->SQL_QUERY;
    $OBJECT_NAME = $var->OBJECT_NAME;
} else {
    $MASTER_OBJECT_ID = '';
    $SQL_QUERY='';
}

$object_list = $db->rs2array("SHOW TABLES");

$selectSQL = "select MASTER_OBJECT_ID, object_name, SQL_QUERY, GRID_TITLE FROM master_object WHERE TR_TYPE=2";
$sql = $selectSQL . ' LIMIT ' . $startpoint . ', ' . $limit;

$rec = query($sql);

//for pagination
$q = explode('FROM', $sql);
$sql_pa = "SELECT COUNT(*) as `num` FROM $q[1]";
$q = explode('LIMIT', $sql_pa);
$sql_pa = "$q[0]";
$row = find($sql_pa);
$total = $row->num;

include("../body/master_header.php");



if ($mode == 'new' || $mode == 'search') {
    ?>
    <form action='create_grid.php' method='POST'>
        <fieldset>
            <legend>Master Module Creator</legend>
            <table>
                <tr>
                    <td width="100">Module Name:</td>
                    <td><?php comboBox('object_id', $object_list, $OBJECT_NAME, FALSE); ?> </td>
                </tr>
                <tr>
                    <td>Grid Title:</td>
                    <td><?php textbox("grid_title", $GRID_TITLE, 20); ?> </td>
                </tr>
                <tr>
                    <td>Body Head:</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan='2'><textarea style="width: 100%; height: 400px;" name='SQL_QUERY' placeholder=''><?php echo $SQL_QUERY; ?></textarea></td>
                </tr>
            </table>
            <br/>

            <?php saveButton() ?>

            <input type="hidden" name="mode" value="<?php echo $mode ?>" />
            <input type="hidden" name="search_id" value="<?php echo $search_id ?>">
        </fieldset>
    </form>
<?php } else { ?>
    <br/><br/>

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
            $("table.ui-state-default").tableFilter();
                
          

        });
    </script>

    <div class="table-header">Master Module Creator</div> 
    <?php grid_top($total, $limit); ?>
    <div class='table-worp' >
        <table id="resultTable" class="ui-state-default">
            <thead>
            <th width="20">SI</th>
            <th width="120">Object Name</th>
            <th width="100">Grid Title</th>
            <th>Display Body</th>
            <th width="80">Action</th>
            </thead>
            <?php
            $sl = 1;

            while ($row = fetch_object($rec)) {

                echo "<tr>";
                echo "<td>$sl</td>";
                echo "<td><a href='create_grid.php?mode=search&search_id=$row->MASTER_OBJECT_ID'>$row->object_name</a></td>";
                echo "<td>$row->GRID_TITLE</td>";
                echo "<td>$row->SQL_QUERY</td>";
                echo "<td align='center'>";
                editIcon("create_grid.php?mode=search&search_id=$row->MASTER_OBJECT_ID");
                echo "</td>";
                echo "</tr>";
                $sl++;
            }
            ?> 
        </table>
    </div>
    <?php
    pagination($total, $page, '?', $limit);
}
include("../body/footer.php");
?>
