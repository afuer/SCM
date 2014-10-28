<?php 
include_once '../lib/DbManager.php';

$user_type_list = query("SELECT user_type_id,user_type_name
FROM user_type
WHERE user_type_id !='1'");
?>

<style type="text/css" media="screen">
    @import "menu/jquery.menu.css";
    @import "menu/stylesheet.css";
</style>
<script type="text/javascript" src="menu/jquery.menu.js"></script>


<?php include '../body/header.php'; ?>

    <h2  style="text-align:center;"> User Type List</h2>
    <table class="ui-state-default">
        <thead >
        <th Width="20">SL</th>
        <th>User Type</th>
        <th width="80">Action</th>
        </thead>
        <tbody>
            <?php while ($row_user_type_list = fetch_object($user_type_list)) {
                ?>
                <tr>
                    <td><?php echo++$SL; ?>.</td>
                    <td><?php echo $row_user_type_list->user_type_name; ?></td>
                    <td><a  href="sysmenu_edit.php?search_id=<?php echo $row_user_type_list->user_type_id; ?>"> Edit </a></td>
                </tr>
            <?php }
            ?>

        </tbody>
    </table>




<?php include '../body/footer.php'; ?>



