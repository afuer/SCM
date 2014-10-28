<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'include.php';
        $id_ = $_GET['id'];
        
        $branch_list=  rs2array(query("SELECT id, branch_name FROM branch"));
        ?>
        <script type="text/javascript">
            function post_value(){ 
                var id = "<?php echo $id_; ?>";
                opener.document.getElementById(id).value = document.getElementById('sed_id').value;
                window.close();
                if (window.opener && !window.opener.closed) {
                    window.opener.location.href = opener.document.getElementById('parent_url').value;
                } 
            }

        </script>

    </head>

    <body>
        <form  name="pp" id="pp" action="" method="GET">
            <?php comboBox('sed_id', $branch_list, $selectedValue, $allowNull)?>
            <input type='text' name='mode' id='sed_id' value=''/>
            <input type="button" value="Topic" onClick="post_value();">
        </form>
    </body>
</html>
