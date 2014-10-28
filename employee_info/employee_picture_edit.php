<?php
include_once '../lib/DbManager.php'; 
$db = new DbManager();
include("../body/header.php");


$Attach_File_Path = getParam('FileName');

function SaveUploadFile1($Request_Id, $Module_Name, $Attach_Title, $Attach_File_Path) {

    $user_name = get('user_name');

    if (isset($Attach_File_Path)) {
        $db = new DbManager();

        foreach ($Attach_File_Path as $key => $val) {
            $db->OpenDb();
            $MaxFile_Attach_List_Id = NextId('file_attach_list', 'FILE_ATTACH_LIST_ID');
          echo  $insert_sql = "INSERT INTO file_attach_list(FILE_ATTACH_LIST_ID, REQUEST_ID, MODULE_NAME, ATTACH_FILE_PATH, CREATED_BY, CREATED_DATE) 
            values('$MaxFile_Attach_List_Id', '$Request_Id', '$Module_Name', '$Attach_File_Path[$key]', '$user_name', NOW() )";
            
            sql($insert_sql);
            $db->CloseDb();
        }
    }
}

if ($_POST) {
    SaveUploadFile1(1, 'ss', $Attach_Title, $Attach_File_Path);
}
?>
<script src="../public/uploadify/jquery.uploadify-3.1.min.js"></script>
<script src="../public/js/jquery.calculation.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var k = 1;
        var AttachmentDetails = '';
        $('#file_upload').uploadify({
            // Some options
            'method': 'post',
            'formData': {
                'id': '1'
            },
            'uploader': 'uploadify.php',
            'buttonClass': 'uploadify-button',
            'buttonText': 'SELECT FILE',
            'onUploadSuccess': function(file, data, response) {
                $('#file_upload_done').val(data);
                $('#file_upload_done').removeClass('text_field_display')
                $('#file_upload').removeClass('uploadify-button').css('display', 'none');
                var FileName = $('#file_upload_done').val();
                $("<tr>" +
                        "<td width='200'><input type='text' value='" + FileName + "' name='FileName[]'/> </td>" +
                        "<td width='200' align='center'><a href='" + FileName + "' class='fancybox'>View </a><div class='remove float-right' onClick='$(this).parent().parent().remove();'>Remove</div></td>" +
                        "</tr>").appendTo("#attachment_tab");
                k++;
                $('#file_upload_done').addClass('text_field_display')
                $('#file_upload').addClass('uploadify-button').css('display', '');
            }
        });

        $('#file_upload_done').css('display', 'none');


        
        
        SelectShowHide();





        $('#attachment_tab .remove').click(function() {
            var val = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: 'ajax_remove_attach_by_id.php?val=' + val,
                success: function(data) {
                }
            });
            $(this).parent().parent().remove();
        });

    });


</script>

<table>
    <tr>
        <td><input type='file' class='uploadify-button' id='file_upload' />
            <input id="file_upload_done" class="text_field_display" type="text" />
        </td>
    </tr>
</table>

<form action="" method="POST">
    <table class="ui-state-default" id="attachment_tab">

        <tbody></tbody>
    </table>
    <button type="submit" class="button" name="save">Save</button>
</form>