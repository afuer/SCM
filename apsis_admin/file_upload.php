<?php
include_once '../lib/DbManager.php';
include '../body/header.php';


echo "<pre>";
print_r($_POST);
?>
<br/><br/>
<script type="text/javascript" src="../public/js/jquery.form.min.js"></script>
<form action="" method="POST">
    <input type="text" name="c" value="" id="upload_file"/><br/>
    <input type="text" name="cdd" value="sdsdsd" id=""/>

    <button type="submit" id="sub" class="button">Submit</button>

</form>


<br/>
<form action="file-echo2.php?mode=q" method="post" enctype="multipart/form-data" id="file_upload_form">
    <input type="file" name="myfile" multiple class="" id="file"><br>
    <input type="submit" value="Upload File to Server" id="s"/>
</form>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
<script src="http://malsup.github.com/jquery.form.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('#file_upload_form').ajaxForm({
            complete: function(xhr) {
                $('#upload_file').val(xhr.responseText);
            }
        });

    });
</script>


<?php
include '../body/footer.php';
?>